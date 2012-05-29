<?php

return array(

	// Main template
	'template' => array('name' => 'template', function($view)
	{
		$config = Config::get('squire');

		// Add the default values from config
		foreach ($config as $var => $value)
		{
			! isset($view->$var) and $view->$var = $value;
		}

		// Title
		if (empty($view->title))
		{
			$prefix = empty($view->page_heading) ? '' : $view->page_heading.' - ';
			$view->title = $prefix.$view->site_name;
		}

		// Nest the meta tags
		$view->meta = View::make('partials.meta')->with('meta', $view->metadata);

		// Attach the main nav
		$view->nav = View::make('partials.nav')->with('items', $view->nav);

		// Search form
		if ( ! empty($config['search']))
		{
			$view->search = View::make('partials.search', $config['search']);
		}
		else
		{
			$view->search = '';
		}

		// Content
		empty($view->content) and $view->content = '';

		// Footer
		! isset($view->footer) and $view->footer = View::make('partials.footer')->with('content', $config['footer_content']);
	}),

	// Nav
	'partials.nav' => function($view)
	{
		// Recursivity ftw!
		$render_item = function($item, $text) {

			! isset($item['attr']['class']) and $item['attr']['class'] = '';

			$url = isset($item['url']) ? $item['url'] : '';
			if (isset($item['uri']))
			{
				if ($item['uri'] == URI::current())
				{
					$item['attr']['class'] += ' active';
				}
				$url = URL::to($item['uri']);
			}

			echo '<li '.HTML::attributes($item['attr']).'><a href="'.$url.'">'.$text.'</a>';

			if (isset($item['submenu']))
			{
				echo '<ul class="submenu">';
				array_walk($item['submenu'], $render_item);
				echo '</ul>';
			}

			echo '</li>';
		};

		$view->render_item = $render_item;
	},

	// Search form
	'partials.search' => function($view)
	{
		// Add the default values from config
		$config = Config::get('squire');
		foreach ($config['search'] as $var => $value)
		{
			! isset($view->$var) and $view->$var = $value;
		}

		isset($view->uri) and $view->url = URL::to($view->uri);
	},

	'partials.main_nav' => array('name' => 'main_nav', function($view)
	{
		// Somehow get the nav items
		$view->items = array(
			'dashboard' => array(
				'text' => 'Dashboard',
				'uri' => 'dashboard',
				'subitems' => array(
					array(
						'text' => 'Browse',
						'uri' => 'clients',
					),
					array(
						'text' => 'Create',
						'uri' => 'clients/new',
					),
				),
			),
		);

		$items = $view->items;

		$hydrate = function(&$item, $key, $hydrate)
		{
			! isset($item['title']) and $item['title'] = $item['text'];
			isset($item['uri']) and $item['url'] = URL::to($item['uri']);
			if (isset($item['subitems']))
			{
				array_walk($item['subitems'], $hydrate, $hydrate);
			}
		};

		array_walk($items, $hydrate, $hydrate);

		$view->items = $items;
	}),

	'partials.sq_js' => function($view)
	{
		$view->base_url = URL::to('/');
		$view->entities = array();
	},

	'partials.table' => array('name' => 'table', function($view)
	{
		$defaults = array(
			'class' => '',
			'columns' => array(),
			'rows' => array(),
			'header_attributes' => array(),
			'header_cell_attributes' => function($column){ return array(); },
			'row_attributes' => function($row){ return array(); },
			'cell_attributes' => function($column, $row){ return array(); },
			'cell_value' => function($column, $row){ 
				if (is_a($row, 'Squire_Model'))
				{
					return $row->value($column);
				}
				return $row->$column;
			},
			'norecords_message' => 'No records',
		);
		foreach ($defaults as $var => $value)
		{
			if ( ! isset($view->$var))
			{
				$view->$var = $value;
			}
		}
	}),

	'partials.keyvalue' => array('name' => 'keyvalue', function($view)
	{
		$defaults = array(
			'class' => '',
			'object' => array(),
			'row_attributes' => function($object, $key){ return array(); },
			'row_label' => function($object, $key){ return $key; },
			'row_value' => function($object, $key){ return $object[$key]; },
		);
		foreach ($defaults as $var => $value)
		{
			if ( ! isset($view->$var))
			{
				$view->$var = $value;
			}
		}
	}),

	'partials.form' => array('name' => 'form', function($view)
	{
		empty($view->submit_text) and $view->submit_text = 'Submit';
		$attr = isset($view->attr) ? $view->attr : array();
		if (isset($attr['uri']))
		{
			$attr['action'] = URL::to($attr['uri']);
			unset($attr['uri']);
		}
		empty($attr['class']) and $attr['class'] = 'form-horizontal';
		empty($attr['method']) and $attr['method'] = 'post';
		$view->attr = $attr;
		
		$buttons = isset($view->buttons) ? $view->buttons : array();
		! is_array($buttons) and $buttons = array($buttons);

		if ( ! empty($view->submit_text))
		{
			$buttons[] = Form::submit($view->submit_text, array('class' => 'btn btn-primary'));
		};

		$view->buttons = implode("\n", $buttons);
	}),

	'partials.panel' => array('name' => 'panel', function($view)
	{
		$attr = array(
			'id' => '',
			'class' => '',
			'data-uri' => '',
		);
		isset($view->attr) and $attr = array_merge($attr, $view->attr);

		isset($view->id) and $attr['id'] = $view->id;
		isset($view->uri) and $attr['data-uri'] = $view->uri;
		isset($view->class) and $attr['class'] .= ' '.$view->class;

		$attr['class'] = trim($attr['class'].' panel');

		$view->attr = $attr;

		! isset($view->buttons) and $view->buttons = array();
		$buttons = array();
		foreach ($view->buttons as $text => $btn)
		{
			! is_array($btn) and $btn = array('uri' => $btn);
			! isset($btn['class']) and $btn['class'] = '';
			$btn['class'] = trim('btn btn-small '.$btn['class']);
			$btn['href'] = URL::to($btn['uri']);
			unset($btn['uri']);
			$buttons[$text] = $btn;
		}
		$view->buttons = $buttons;
	}),

	'partials.modal' => array('name' => 'modal', function($view)
	{
		$attr = array(
			'class' => '',
		);
		isset($view->attr) and $attr = array_merge($attr, $view->attr);
		
		$attr['class'] = trim('modal hide '.$attr['class']);
		$view->attr = $attr;

		$default_buttons = array(
			'Save' => array(
				'class' => 'btn-primary',
			),
			'Cancel' => array(
				'data-dismiss' => 'modal',
			),
		);

		$buttons = array();
		! isset($view->buttons) and $view->buttons = $default_buttons;
		foreach ($view->buttons as $text => $attr)
		{
			if (isset($attr['uri']))
			{
				$attr['href'] = URL::to($attr['uri']);
				unset($attr['uri']);
			}
			! isset($attr['href']) and $attr['href'] = '#';

			! isset($attr['class']) and $attr['class'] = '';
			$attr['class'] = trim('btn '.$attr['class']);

			$buttons[$text] = $attr;
		}
		$view->buttons = array_reverse($buttons);
	}),

);