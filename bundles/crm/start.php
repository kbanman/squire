<?php

Laravel\Autoloader::map(array(
	// Controllers
	'Crm_Clients_Controller'        => __DIR__.DS.'/controllers/clients.php',
	'Crm_Communications_Controller' => __DIR__.DS.'/controllers/communications.php',
	'Crm_Comments_Controller'       => __DIR__.DS.'/controllers/comments.php',
	 // Models
	'Crm\\Client'        => __DIR__.DS.'/models/client.php',
	'Crm\\Communication' => __DIR__.DS.'/models/communication.php',
	'Crm\\Comment'       => __DIR__.DS.'/models/comment.php',
));

use Crm\Client;
use Crm\Communication;
use Crm\Comment;

Route::any('clients/(:num)', function($client_id)
{
	return Route::forward(Request::method(), 'clients/client/'.$client_id);
});
Route::get('clients/(:num)/new_communication', function($client_id)
{
	return Route::forward('get', 'communications/form/'.$client_id);
});
Route::post('clients/(:num)/communication', function($client_id)
{
	return Route::forward('post', 'communications/form/'.$client_id);
});
Route::get('clients/(:num)/communications', function($client_id)
{
	return Route::forward('get', 'communications/for_client/'.$client_id);
});
Route::controller(array('crm::clients', 'crm::communications', 'crm::comments', 'crm::leads'));


// Add action buttons
Section::append('header_buttons', View::make('crm::partials.action_buttons'));

// Add main nav
Section::append('nav', View::make('crm::partials.main_nav'));

include 'search.php';


// Show comments on client pages
Event::listen('content_panels', function($type, Client $client)
{
	$route = new Route('GET', 
		'clients/'.$client->id.'/comments', 
		array('uses' => 'crm::comments@for_client'), 
		array($client));

	Section::append('content', $route->call()->render());
});


// Show communications on client pages
Event::listen('content_panels', function($type, Client $client)
{
	$route = new Route('GET', 
		'clients/'.$client->id.'/communications',
		array('uses' => 'crm::communications@for_client'),
		array($client));

	Section::append('content', $route->call()->render());
});
