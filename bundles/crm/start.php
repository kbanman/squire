<?php

Laravel\Autoloader::map(array(
	// Controllers
	'Crm_Clients_Controller'        => __DIR__.DS.'/controllers/clients'.EXT,
	'Crm_Communications_Controller' => __DIR__.DS.'/controllers/communications'.EXT,
	'Crm_Comments_Controller'       => __DIR__.DS.'/controllers/comments'.EXT,
	'Crm_Leads_Controller'          => __DIR__.DS.'/controllers/leads'.EXT,
	 // Models
	'Crm\\Client'        => __DIR__.DS.'/models/client'.EXT,
	'Crm\\Communication' => __DIR__.DS.'/models/communication'.EXT,
	'Crm\\Comment'       => __DIR__.DS.'/models/comment'.EXT,
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
//Section::inject('header_buttons', View::make('crm::partials.action_buttons'));

// Add main nav
Section::append('nav', View::make('crm::partials.main_nav'));

function highlight_term($needle, $haystack)
{
	$start = stripos($haystack, $needle);
	$before = substr($haystack, 0, $start);
	$term = substr($haystack, $start, strlen($needle));
	$after = substr($haystack, $start + strlen($needle));

	return $before.'<span class="highlight">'.$term.'</span>'.$after;
}

// Respond to searches
Event::listen('squire_search', function($search_string, $terms)
{
	if ( ! Auth::check()) return;
	
	$results = array('clients' => array());

	$query = DB::table('clients')
		->select(array('clients.*', DB::raw("CONCAT(client_contacts.name_first,' ',client_contacts.name_last) AS contact_name")))
		->where(function($query) use ($terms)
		{
			foreach ($terms as $term)
			{
				$query->or_where('business_name', 'like', '%'.$term.'%');
			}
		})
		->or_where(function($query) use ($terms)
		{
			foreach ($terms as $term)
			{
				$query->or_where('client_contacts.name_first', 'like', '%'.$term.'%');
				$query->or_where('client_contacts.name_last', 'like', '%'.$term.'%');
			}
		})
		->left_join('client_contacts', 'clients.id', '=', 'client_contacts.client_id');
	
	$clients = $query->distinct()->get();

	// Now we do some more work to determine the context of each result
	foreach ($clients as $client)
	{
		$score = 0;
		$context = null;
		$label = $client->business_name; 
		
		// Business name contains the whole query
		(stripos($client->business_name, $search_string) !== false) && $score += 3;

		// Contact name contains the whole query
		if (stripos($client->contact_name, $search_string) !== false)
		{
			$score += 2;
			$context = 'Contact: '.highlight_term($search_string, $client->contact_name);
		}

		foreach ($terms as $term)
		{
			if (($pos = stripos($client->business_name, $term)) !== false)
			{
				$score += 2;
				$label = highlight_term($term, $label);
				// Subtract points if not at the start of a word
				($pos && substr($client->business_name, $pos-1, 1) != ' ') && $score--;
			}

			if (($pos = stripos($client->contact_name, $term)) !== false)
			{
				$score++;
				empty($context) && $context = 'Contact: '.highlight_term($term, $client->contact_name);
				// Subtract points if not at the start of a word
				($pos && substr($client->contact_name, $pos-1, 1) != ' ') && $score--;
			}
		}

		$results['clients'][] = array(
			'score' => $score,
			'context' => $context,
			'uri' => 'clients/'.$client->id,
			'label' => $label,
		);
	}

	// Just to burn some more CPU cycles, sort them by score
	usort($results['clients'], function($a, $b)
	{
		if ($a['score'] == $b['score']) return 0;
		return ($a['score'] < $b['score']) ? 1 : -1;
	});

	return array(
		array(
			'index_uri' => 'clients',
			'label' => 'Clients',
			'results' => $results['clients'],
		),
	);
});


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
