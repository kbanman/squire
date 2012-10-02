<?php

Laravel\Autoloader::map(array(

	// Controllers
	'Squire_Controller' => __DIR__.DS.'controllers'.DS.'squire.php',

	// Libraries
	'Squire\\Request' => __DIR__.DS.'libraries'.DS.'request.php',
	'Squire\\Response' => __DIR__.DS.'libraries'.DS.'response.php',

));

class_alias('Squire\\Request', 'Request');
class_alias('Squire\\Response', 'Response');

// Load config
Config::get('squire::squire');

function squire_extend_config($key, $value)
{
	if (is_array($value) && ($key != 'nav'))
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

// Insert Squire JS
Section::append('head', View::make('squire::sq_js')->render());


include __DIR__.DS.'composers.php';