<?php

use Crm\Client;

/*
 * CRM Clients Controller
 */
class Crm_Clients_Controller extends \Protected_Controller {

	public $restful = true;
	
	public function get_index()
	{
		// Paginated list of clients
		$clients = Client::paginate(10);

		$table = Squi\Table::of('Crm\\Client')
			->with('rows', $clients->results)
			->with('row_attr', function($client)
			{
				return array(
					'class' => 'clickable',
					'data-uri' => 'clients/'.$client->id,
				);
			})
			->with('class', array('table', 'table-compact'));

		$panel = View::of('panel')
			->with('content', $table);


		Section::append('content', $panel."\n".$clients->links());

		$layout = View::make('template')
			->with('page_heading', 'Clients');
		
		return $layout;
		
	}

	/*
	 * Retrieve specific client record
	 */
	public function get_client($client_id)
	{
		// Client detail
		if ( ! $client = Client::find($client_id))
		{
			return Response::error(404);
		}
		
		$details = View::of('keyvalue')
			->with('object', $client->properties_display())
			->with('row_label', function($object, $key){ return $object[$key]['label']; })
			->with('row_value', function($object, $key){ return $object[$key]['value']; });

		Section::append('content', $details->render());

		// Add panels
		Section::append('content', '<div class="row">');
		Event::fire('content_panels', array('clients_detail', $client));
		Section::append('content', '</div>');

		$layout = \View::make('template')->with('page_heading', $client->name());
		
		return $layout;
		
	}

}