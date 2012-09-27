<?php

/**
 * Determines whether a variable is an associative
 * array based on the first element's key being an
 * integer 0.
 */
function is_assoc_array($arr)
{
	if ( ! is_array($arr)) return false;
	$keys = array_keys($arr);

	// If it's empty, I guess it could be an assoc
	if ( ! count($keys)) return true;

	// Judging the array by it's cover
	return (reset($keys) !== 0);
}

// Fetch the squire config once
$config = Config::get('squire::squire', $default=array());

// The main template
View::composer($config['partials']['template'], function($view) use ($config)
{
	// Recursively inject template variables into a view
	$inject_var = null;
	$inject_var = function($view, $key, $value) use (&$inject_var)
	{
		/*
		if (is_assoc_array($value))
		{
			if ( ! isset($view->))
			foreach ($value as $_key => $_value)
			{
				$inject_var($view, $_key, $_value);
			}

			return;
		}
		*/

		if ( ! isset($view->$key))
		{
			$view->$key = $value;
		}
	};

	// Apply the Squire config as default template vars
	foreach ($config as $key => $value)
	{
		$inject_var($view, $key, $value);
	}

	// Page Title
	if ( ! isset($view->page_title))
	{
		$view->page_title = sprintf($config['title'], $view->page_heading);
	}

	// The charset will be referenced outside of the metadata loop
	$view->charset = $config['metadata']['charset'];
	unset($view->data['metadata']['charset']);

	// Nest the partials
	foreach (array('top', 'header', 'container', 'footer') as $partial)
	{
		if ($path = $config['partials'][$partial])
		{
			$view->$partial = View::make($path, $view->data)->render();
		}
		else
		{
			$view->$partial = '';
		}
	}

	// @todo: the content variable will have a different
	// meaning before and after the 'content' partial
	// has been rendered and added to the view
});

// Top bar
View::composer($config['partials']['top'], function($view) use ($config)
{
	// Nest the partials
	foreach (array('logo', 'main_nav', 'search') as $partial)
	{
		if ($path = $config['partials'][$partial])
		{
			$view->$partial = View::make($path, $view->data)->render();
		}
		else
		{
			$view->$partial = '';
		}
	}
});

// Main Nav
View::composer($config['partials']['main_nav'], function($view) use ($config)
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
});

// Search
View::composer($config['partials']['search'], function($view) use ($config)
{
	foreach ($config['search'] as $var => $value)
	{
		! isset($view->$var) and $view->$var = $value;
	}

	isset($view->uri) and $view->url = URL::to($view->uri);
});

// Container
View::composer($config['partials']['container'], function($view)
{
	$config = Config::get('squire::squire');

	foreach (array('page_head', 'content', 'page_foot') as $partial)
	{
		if ($path = $config['partials'][$partial])
		{
			$view->$partial = View::make($path, $view->data)->render();
		}
		else
		{
			$view->$partial = '';
		}
	}
});

