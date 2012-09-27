<?php

Laravel\Autoloader::map(array(

	// Controllers
	'Squire_Controller' => __DIR__.DS.'controllers'.DS.'squire.php',

));

// Load config
Config::get('squire::squire');

function squire_extend_config($key, $value)
{
	if (is_array($value))
	{
		foreach ($value as $key1 => $value2)
		{
			squire_extend_config($key.'.'.$key1, $value2);
		}

		return;
	}

	Config::set('squire::squire.'.$key, $value);
}

// Apply app config
foreach (Config::get('squire', array()) as $key => $value)
{
	squire_extend_config($key, $value);
}


include __DIR__.DS.'composers.php';