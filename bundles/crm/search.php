<?php
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