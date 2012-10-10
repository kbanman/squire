<?php namespace Squire;

class Request extends \Laravel\Request {
	
	// @todo: support an array argument	
	public static function prefers($mime)
	{
		$mimes = \Config::get('mimes');

		$accept = static::accept();

		$preferred = reset($accept);

		$mime = isset($mimes[$mime]) ? $mimes[$mime] : $mime;

		if ( ! is_array($mime)) $mime = array($mime);

		return in_array($preferred, $mime);
	}

}