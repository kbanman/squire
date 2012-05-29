<?php

use Crm\Client;
use Crm\Submission;

/*
 * CRM Leads Controller
 */
class Crm_Leads_Controller extends \Protected_Controller {

	public $restful = true,
			$layout = 'template';
	
	/**
	 * Get leads
	 */
	
	public function get_index()
	{

		Asset::container('footer')->add('jquery-scrollto', 'bundles/crm/js/jquery.scrollto-1.4.2.min.js');
		Asset::container('footer')->add('leads_submit', 'bundles/crm/js/leads_view.js', 'jquery-scrollto');
		
		// Get the first 10 clients for testing 
		$clients = Client::take(10)->get();
		$table = Squi\Table::of('Crm\\Client')
			->with('columns', array(
				'Name' => function($row) { return $row->name(); },
				'address_street' => 'Address',
				'phone_main' => 'Home Phone',
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
			->with('content', View::make('crm::partials.leads.browse')->with('clients', $table));

		Section::append('content', $panel);

		$this->layout->page_heading = 'View Leads';
	}
	
	/**
	 * Lead details
	 * @param int $lead_id
	 */
	public function get_lead($lead_id = null)
	{
		if ( ! $lead = Client::find($lead_id))
		{
			return Response::error(404);
		}
		
		return View::make('crm::partials.leads.details')->with('lead', $lead);
	}
	
	/**
	 * Sumbit a new lead
	 */
	public function get_submit()
	{
		Asset::container('footer')->add('leads_submit', 'bundles/crm/js/leads_submit.js');

		$panel = View::of('panel')
			->with('content', View::make('crm::partials.leads.submit'));

		Section::append('content', $panel);

		$this->layout->page_heading = 'Submit Lead';
	}
}