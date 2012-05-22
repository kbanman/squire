<?php

use Crm\Client;
use Crm\Communication;

/*
 * CRM Communications Controller
 */
class Crm_Communications_Controller extends \Protected_Controller {
	
	public $restful = true;

	// Communications Panel for client detail page
	public function get_for_client($client)
	{
		if ( ! is_a($client, 'Crm\\Client') && ! $client = Client::find($client))
		{
			return Response::error(400);
		}

		// Get this client's communications
		$calls = Communication::with('user')
			->where('client_id', '=', $client->id)
			->order_by('datetime', 'desc')
			->get();
		
		$table = View::of('table')
			->with('columns', Communication::columns_panel())
			->with('rows', $calls);

		$buttons = array(
			'<i class="icon-plus"></i> New Communication' => array(
				'uri' => 'clients/'.$client->id.'/new_communication',
				'class' => 'ajax',
			),
		);

		$panel = View::of('panel')
			->with('id', 'client_communications')
			->with('class', 'span6')
			->with('uri', 'clients/'.$client->id.'/communications')
			->with('title', 'Communications')
			->with('content', $table)
			->with('buttons', $buttons);
		
		return $panel;
	}

	// New communication form
	public function get_form($client_id)
	{
		if ( ! $customer = Client::find($client_id))
		{
			return Response::error(500, 'Invalid Client ID');
		}

		// Blank Communication
		$comm = new Communication;
		$comm->client_id = $customer->id;

		// Create the form
		$form = \Squi\Form::of($comm)
			->with('form_attr', array(
				'uri' => 'clients/'.$customer->id.'/communication',
				'method' => 'post',
				'class'  => 'form-horizontal validate',
			))
			->with('legend', 'Log a new client communication')
			->with('layout', 'bootstrap');

		// Decide on presentation method
		if (Request::ajax())
		{
			$form->buttons = false;
			$form->legend = false;
			$form->modal = true;

			$modal = View::of('modal')
				->with('title', 'New Communication')
				->with('content', $form)
				->with('attr', array('class' => 'form-modal'))
				->with('buttons', array(
					'Save Communication' => array('class' => 'btn-primary'),
					'Cancel' => array('data-dismiss' => 'modal'),
				));

			return $modal;
		}
		$layout = View::of('template')
			->with('title', 'New Client Communication')
			->with('content', '<div class="row">'.$form.'</div>');
			
		return $layout;
	}

	// Process communication form
	public function post_form($client_id)
	{
		$status = 200;
		$errors = array();

		// Validate Client ID
		if ( ! $client = Client::find($client_id))
		{	
			$status = 500;
			$errors[] = 'Invalid client id';
		}
		else
		{
			// Blank Communication
			$comm = new Communication;
			$comm->fill( Input::all() );
			$comm->client_id = $client->id;
			$comm->user_id = Auth::user()->id;

			// Validate and save
			if ($success = $comm->validate())
			{
				// Convert the date to a timestamp
				// @todo: this could be done a much better way
				if ( ! $success = $comm->save())
				{
					$status = 500;
					$errors[] = 'Error saving to database';
				}
			}
			else
			{
				// Validation error
				$status = 400;
				$errors = $comm->validation_errors();
			}
		}
		
		// Ajax response
		if (Request::ajax())
		{
			if ($success)
			{
				$data = array(
					'status' => 'success',
					'message' => 'Saved new client communication',
					'reload_panel' => 'client_communications',
				);
			}
			else
			{
				$data = array(
					'status' => 'error',
					'errors' => $errors,
				);
			}
			return Response::make(json_encode($data), $status);
		}

		// HTML response
		if ($success)
		{
			$errors = new Laravel\Messages;
			Session::flash('success', 'Saved new client communication');
		}
		else
		{
			$errors = $comm->validation_errors($object = true);
			($status == 500) and $errors->add('server', reset($errors));
		}

		$redirect = array_get($_SERVER, 'HTTP_REFERER', null);
		if (empty($redirect))
		{
			var_dump($errors);
			die();
		}

		// Redirect back to whence you came
		return Redirect::to($redirect)->with_errors($errors);
	}

}
