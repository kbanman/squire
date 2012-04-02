<?php

class Squire_Model extends Eloquent\Model {
	
	/**
	 * Holds a description of the model's fields
	 * for form and display purposes
	 */
	public static $_properties = array();

	public $validator;

	public function properties_display()
	{
		$props = array();

		foreach (static::$_properties as $prop => $s)
		{
			if (isset($s['display']) and $s['display'] === false)
			{
				continue;
			}

			$props[$prop] = array(
				'label' => static::label($prop),
				'value' => $this->value($prop),
			);
		}

		return $props;
	}

	public function properties_form()
	{
		$props = array();

		foreach (static::$_properties as $prop => $s)
		{
			if (isset($s['form']) and $s['form'] === false)
			{
				continue;
			}

			$field = isset($s['form']) ? $s['form'] : array();
			$attr = isset($field['attr']) ? $field['attr'] : array();
			$type = isset($field['type']) ? $field['type'] : 'text';

			! isset($s['label']) and $s['label'] = ucfirst(str_replace('_', ' ', $prop));
			$label = ($type == 'hidden') ? null : Form::label($prop, $s['label']);

			$value = $this->value($prop);

			$args = array($prop, $value);
			if ($type == 'select')
			{
				$args[] = $this->get_options($prop);
			}

			// Add attributes and client-side validation classes
			if (isset($s['validation']) or ! empty($attr))
			{
				! isset($attr['class']) and $attr['class'] = '';
				$val = array();

				// If data-validation is explicitly specified, stick with that.
				if ( ! empty($s['validation']) and empty($attr['data-validation']))
				{
					$val = $s['validation'];
				}
				elseif( ! empty($attr['data-validation']))
				{
					$val = $attr['data-validation'];
				}

				$attr['data-validation'] = json_encode($val);

				(array_search('required', $val) !== false) and $attr['class'] .= ' required';

				$args[] = $attr;
			}

			$markup = call_user_func_array(array('Form', $type), $args);

			$props[$prop] = array(
				'label' => $label,
				'field' => $markup,
			);
		}

		return $props;
	}

	public static function label($prop)
	{
		extract(static::$_properties[$prop]);

		$label = isset($label) ? $label : ucfirst(str_replace('_', ' ', $prop));
		is_callable($label) and $label = $label($this);
		return $label;
	}

	public function value($prop, $default = '')
	{
		extract(static::$_properties[$prop]);

		// Options list
		if (isset($form['type']) and $form['type'] == 'select')
		{
			return $this->get_options($prop, $this->$prop);
		}
		elseif (isset($display) and is_callable($display))
		{
			return $display($this);
		}
		
		return is_null($this->$prop) ? $default : $this->$prop;
	}

	/**
	 * Gets an array of options for a property with a "select" form type
	 * Queries the database if an "options_table" is configured
	 *
	 * If $where_value is specified, only the corresponding label is returned
	 */
	protected function get_options($prop, $where_value = null)
	{
		extract(static::$_properties[$prop]);

		if (isset($form['options']))
		{
			if ( ! is_null($where_value))
			{
				return $form['options'][$where_value];
			}
			return $form['options'];
		}

		$config = array(
			'pk' => 'id',
			'label_field' => 'label',
			'value_field' => 'value',
			'where' => array(),
		);
		! is_array($options_table) and $options_table = array('table' => $options_table);
		$config = array_merge($config, $options_table);

		// If $where_value is specified, restrict results to that record
		! is_null($where_value) and $config['where'][$config['value_field']] = $where_value;

		// Build the WHERE clause
		$where = '';
		if (count($config['where']))
		{
			foreach ($config['where'] as $field => &$value)
			{
				$value = sprintf("% = '%s'", $field, $value);
			}
			$where = ' WHERE '.implode(' AND ', $config['where']);
		}

		$result = DB::query(sprintf("SELECT * FROM %s%s", $config['table'], $where));

		$options = array();
		foreach ($result as $opt)
		{
			$options[$opt->{$config['value_field']}] = $opt->{$config['label_field']};
		}

		// Add the prompt if specified
		if (isset($form['select_prompt']))
		{
			$prompt = is_array($form['select_prompt']) ? $form['select_prompt'] : array('' => $form['select_prompt']);
			$options = $prompt + $options;
		}

		// Cache options for future use
		static::$_properties[$prop]['form']['options'] = $options;

		if ( ! is_null($where_value))
		{
			return $options[$where_value];
		}

		return $options;
	}

	public function form($attr = array(), $view = 'partials.form')
	{
		// Compile fields and labels
		$props = $this->properties_form();
		return View::make($view)
			->with('fields', $props)
			->with('attr', $attr);
	}

	public static function table_columns($cols = array())
	{
		if (empty($cols))
		{
			$cols = array();
			foreach (static::$_properties as $prop => $s)
			{
				if (isset($s['display']) and $s['display'] === false) continue;
				$cols[] = $prop;
			}
		}

		$columns = array();
		foreach ($cols as $col)
		{
			$columns[$col] = static::label($col);
		}
		return $columns;
	}

	public static function weight_class($property)
	{
		$weight = isset(static::$_properties[$property]['weight']) ? static::$_properties[$property]['weight'] : 'normal';
		return 'weight-'.$weight;
	}

	public function populate($input = array())
	{
		$input = (array) $input;
		foreach ($input as $prop => $value)
		{
			if ( ! array_key_exists($prop, static::$_properties)) continue;
			$this->$prop = $value;
		}
	}

	public function validate()
	{
		return $this->validator()->valid();
	}

	public function validation_errors($as_obj = false)
	{
		$errors = $as_obj ? new Laravel\Messages : array();
		foreach ($this->validator->errors->messages as $prop => $messages)
		{
			foreach ($messages as &$message)
			{
				$message = str_replace($prop, static::label($prop), $message);
			}
			if ($as_obj)
			{
				$errors->messages[$prop] = $messages;
			}
			else
			{
				$errors = array_merge($errors, $messages);
			}
		}
		return $errors;
	}

	protected function validator()
	{
		$rules = array();
		foreach (static::$_properties as $prop => $s)
		{
			if ( ! isset($s['validation'])) continue;
			$rules[$prop] = $s['validation'];
		}
		return $this->validator = Validator::make($this->dirty, $rules);
	}

}