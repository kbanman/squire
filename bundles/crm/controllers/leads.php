<?php

use Crm\Client;
use Crm\Submission;

/*
 * CRM Leads Controller
 */
class Crm_Leads_Controller extends \Protected_Controller {

	public $restful = true;
	
	/**
	 * Get leads
	 */
	
	public function get_index()
	{

		Asset::container('footer')->add('leads_submit', 'js/leads_view.js');
		Asset::container('footer')->add('scrollTo', 'js/jquery.scrollTo-1.4.2-min.js','leads_submit');
		
		// Get the first 10 clients for testing 
		$clients = Client::take(10)->get(array('id','business_name','address_street','phone_main','type'));
		$table = Squi\Table::of('Crm\\Client')
            ->with('columns', array(
                  'name'       => array(
                        'heading' => 'Name',
                        'value' => function($row) {return $row->business_name;},
                        ),
                  'Address'    => array(
                        'heading' => 'Address',
                        'value' => function($row) {return $row->address_street;}
                        ),
                  'Home Phome' => array(
                        'heading' => 'Home Phome',
                        'value' => function($row) {return $row->phone_main;}
                        ),
                  'Type'       => array(
                        'heading' => 'Type',
                        'value' => function($row) {return $row->type;}
                        ),
                  'Status'     => array(
                        'heading' => 'Status',
                        'value' => function($row) {return 'A';}
                        )
                ))
			->with('rows', $clients)
			->with('row_attr', function($client)
			{
				return array(
					'class'       => 'clickable leadrow',
					'data-leadid' => $client->id,
				);
			})
			->with('class', array('table', 'table-condensed'));
			
			
		$panel = View::of('panel')
			->with('content', View::make('partials.leads.browse')->with('clients', $table));


		Section::append('content', $panel);

		$layout = View::make('template')
			->with('page_heading', 'View Leads');
		
		return $layout;
	}
	
	/**
	 * Lead details
	 * @param int $lead_id
	 */
	
	public function post_details($lead_id = null) {
		
			// Don't know how you want to approach error handling
			if(is_null($lead_id) OR !Request::ajax())
				return 'ERROR: Lead ID cannot be null / Not an ajax request';
			
			$lead = Client::find(500);
			
			if(!is_null($lead))
			{
				if(Request::ajax()) {
					return View::make('partials.leads.details')
							->with('lead',$lead);
				}
			}
			
			return '<h1>Lead not found</h1>';

	}
	
	/**
	 * Sumbit a new lead
	 */
	
	public function get_submit()
	{
		Asset::container('footer')->add('leads_submit', 'js/leads_submit.js');

		$panel = View::of('panel')
					->with('content', View::make('partials.leads.submit'));
					
		Section::append('content', $panel);

		$layout = View::make('template')
				  ->with('page_heading', 'Submit Lead');
				  
		return $layout;
	}
}