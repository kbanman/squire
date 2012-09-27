<?php

class Squire_Controller extends Controller {

	public function __construct()
	{
		$this->layout = Config::get('squire::squire.partials.template');
		parent::__construct();
	}

}