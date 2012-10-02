<?php

class Home_Controller extends Base_Controller {

	public function get_index()
	{
		$this->layout->page_heading = 'Page Heading';
		$this->layout->content = 'test';
	}

}