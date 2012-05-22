<?php

class Squire_Model extends Eloquent {
	
	/**
	 * Holds a description of the model's fields
	 * for form and display purposes
	 */
	public static $_properties = array();

	public $validator;

	public function __construct($attributes = array(), $exists = false)
	{
		// Dynamically construct the list of accessible properties
		static::$accessible = array();
		foreach (static::$_properties as $key => $value)
		{
			// Assume accessible by default
			if ( ! isset($value['accessible']) || $value['accessible'])
			{
				static::$accessible[] = $key;
			}
		}

		parent::__construct($attributes, $exists);
	}

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

	public static function form_fields($form = null)
	{
		$props = array();

		foreach (static::$_properties as $prop => $s)
		{
			// Explicitly-excluded fields
			if (isset($s['form']) && $s['form'] === false)
			{
				continue;
			}

			$field = isset($s['form']) ? $s['form'] : array();
			empty($field['label_attr']) && $field['label_attr'] = array();
			isset($field['label_attr']['class']) || $field['label_attr']['class'] = array();
			empty($field['field_attr']) && $field['field_attr'] = array();
			isset($field['field_attr']['class']) || $field['field_attr']['class'] = array();
			empty($field['type']) && $field['type'] = 'text';
			isset($field['name']) || $field['name'] = $prop;

			isset($s['label']) && $field['label'] = $s['label'];
			
			// This might not be necessary (I think Squi\Form does this)
			if (isset($form) && is_a($form->instance, 'Squire\\Model'))
			{
				$field['value'] = $form->instance->value($prop);
			}

			// Add potentially dynamic options for <select> fields
			($field['type'] == 'select') && $field['options'] = static::get_options($prop);

			// Convert data- attributes to faux json
			foreach ($field['field_attr'] as $key => &$value)
			{
				if (strpos($key, 'data') !== 0 || ! is_array($value)) continue;

				// @todo: converting all double quotes to single quotes could easily
				// mangle valid json, so this should be done another way,
				// possibly using the JSON_HEX_QUOT flag?
				$value = str_replace('"', '\'', json_encode($value));
			}

			// Add attributes and client-side validation classes
			if (isset($s['validation']))
			{
				$classes = array();

				if (array_search('required', $s['validation']) !== false)
				{
					$classes[] = 'required';
					$field['field_attr']['required'] = 'required';
				}

				if (count($classes))
				{
					append_class($field['field_attr']['class'], $classes, true);
					append_class($field['label_attr']['class'], $classes, true);
				}
			}

			$props[$prop] = $field;
		}

		return $props;
	}

	// Maybe deprecated
	public static function label($prop)
	{
		extract(static::$_properties[$prop]);

		$label = isset($label) ? $label : ucfirst(str_replace('_', ' ', $prop));
		is_callable($label) && $label = $label($this);
		return $label;
	}

	public function value($prop, $default = '')
	{
		extract(static::$_properties[$prop]);

		// Options list
		if (isset($form['type']) and $form['type'] == 'select')
		{
			return static::get_options($prop, $this->$prop);
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
	protected static function get_options($prop, $where_value = null)
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
		! is_array($options_table) && $options_table = array('table' => $options_table);
		$config = array_merge($config, $options_table);

		$query = DB::table($config['table']);

		// If $where_value is specified, restrict results to that record
		! is_null($where_value) && $query->where($config['value_field'], '=', $where_value);

		// Build the WHERE clause
		foreach ($config['where'] as $field => $value)
		{
			$query->where($field, '=', $value);
		}

		// Fetch and process result
		foreach ($query->get() as $opt)
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

	public static function table_columns($table = null)
	{
		$cols = array();
		foreach (static::$_properties as $prop => $s)
		{
			if (isset($s['display']) and $s['display'] === false) continue;

			$col = isset($s['form']) ? $s['form'] : array();

			isset($s['label']) && $col['heading'] = $s['label'];

			isset($col['heading_attr']) || $col['heading_attr'] = array();
			isset($col['heading_attr']['class']) || $col['heading_attr']['class'] = array();
			append_class($col['heading_attr']['class'], static::weight_class($prop), true);

			isset($col['cell_attr']) || $col['cell_attr'] = array();
			isset($col['cell_attr']['class']) || $col['cell_attr']['class'] = array();
			append_class($col['cell_attr']['class'], static::weight_class($prop), true);

			$cols[$prop] = $col;
		}

		return $cols;
	}

	public static function weight_class($column)
	{
		$weight = 'normal';

		if (isset(static::$_properties[$column]['weight']))
		{
			$weight = static::$_properties[$column]['weight'];
		}
		
		return 'weight-'.$weight;
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
		return $this->validator = Validator::make($this->attributes, $rules);
	}

}