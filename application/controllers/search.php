<?php

class Search_Controller extends Base_Controller {

	public $restful = true;
	
	public function get_index()
	{
		$query = Input::get('q');

		if (empty($query))
		{
			if (Request::ajax())
			{
				return json_encode(array());
			}
			return 'Must enter a query';
		}

		// Split the query into terms
		$terms = array();
		foreach (explode(' ', $query) as $term)
		{
			// Don't include crappy terms
			if ( ! in_array(strtolower($term), array('and', ',', 'the', 'a', 'or', '&', 'to', 'in', 'on')))
			{
				$terms[] = $term;
			}
		}

		$results = Event::fire('squire_search', array($query, $terms));

		$categories = array();

		foreach ($results as $result)
		{
			if ( ! is_array($result)) continue;

			foreach ($result as $category)
			{
				if (empty($category['results'])) continue;

				// Arbitrary result limiting
				$limit = 6;
				if (count($category['results']) > $limit)
				{
					$category['results'] = array_slice($category['results'], 0, $limit);
				}

				if ( ! isset($categories[$category['index_uri']]))
				{
					$categories[$category['index_uri']] = $category;
				}
				else
				{
					array_merge($categories[$category['index_uri']]['results'], $category['results']);
				}
			}
		}

		if (Request::ajax())
		{
			return json_encode($categories);
		}

		echo '<pre>';
		var_dump($categories);
		echo '</pre>';
	}

}