<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Layout Options
	|--------------------------------------------------------------------------
	|
	| The twitter bootstrap supports a few different modes for layouts,
	| including 'responsive' and 'fluid'.
	|
	*/

	'responsive_layout' => true,


	/*
	|--------------------------------------------------------------------------
	| Site Name 
	|--------------------------------------------------------------------------
	|
	| HTML that constitutes the site logo. It is wrapped in a link to the
	| app homepage.
	|
	*/

	'site_name' => '<span style="color:#fff">Squire</span>App',


	/*
	|--------------------------------------------------------------------------
	| Page Title
	|--------------------------------------------------------------------------
	|
	| Template for the page title text. It is run through sprintf and passed
	| the page_title variable.
	| 
	| This format can be overriden by explicitly defining the 'title' variable
	| on the template or changing via runtime configuration.
	|
	*/

	'title' => "%s | SquireApp",


	/*
	|--------------------------------------------------------------------------
	| Meta Data
	|--------------------------------------------------------------------------
	|
	| Default values for metadata content.
	| 
	| Page-specific values can be set using runtime configuration
	| ex. Config::set('squire::metadata.keywords', $keywords);
	|
	*/

	'metadata' => array(
		'charset' => 'utf-8',
		'viewport' => 'width=device-width, initial-scale=1.0',
		'description' => '',
		'author' => '',
		'keywords' => '',
	),


	/*
	|--------------------------------------------------------------------------
	| Language
	|--------------------------------------------------------------------------
	|
	| Affects the 'lang' attribute of the <html> tag.
	|
	*/
	'lang' => 'en',


	/*
	|--------------------------------------------------------------------------
	| Search
	|--------------------------------------------------------------------------
	|
	| Configures the app-wide search form. Set to false to disable.
	|
	*/
	'search' => array(
		'uri' => 'search',
		'name' => 'q',
		'method' => 'GET',
		'placeholder' => 'Search',
	),


	/*
	|--------------------------------------------------------------------------
	| Main Navigation
	|--------------------------------------------------------------------------
	|
	| Configures the top nav bar items
	|
	*/
	'nav' => array(
		'Customers' => array(
			'uri' => 'customers',
		),
	),

	// Partial view Files
	'partials' => array(
		'template' => 'squire::bootstrap',
		'top' => 'squire::partials.top',
			'logo' => 'squire::partials.logo',
			'main_nav' => 'squire::partials.main_nav',
			'search' => 'squire::partials.search',
		'header' => '',
		'container' => 'squire::partials.container',
			'page_head' => 'squire::partials.page_head',
			'content' => 'squire::partials.content',
			'page_foot' => '',
		'footer' => '',
	),


	// Initialize template vars
	'page_heading' => null,
	'content' => '',
	'message' => null,
	'errors' => null,

);