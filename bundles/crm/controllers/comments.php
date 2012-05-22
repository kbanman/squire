<?php

use Crm\Client;
use Crm\Comment;

/*
 * CRM Comments Controller
 */
class Crm_Comments_Controller extends \Protected_Controller {
	
	public $restful = true;

	/*
	 * Comments panel for client
	 */
	public function get_for_client($client)
	{
		if ( ! is_a($client, 'Crm\\Client') && ! $client = Client::find($client))
		{
			return Response::error(400);
		}

		// Get us some comments
		$comments = Comment::with('user')
			->where('entity', '=', 'Crm\\Client')
			->where('entity_id', '=', $client->id)
			->order_by('created_at', 'desc')
			->get();

		$panel = View::make('crm::comments')
			->with('id', 'customer_comments')
			->with('uri', 'customers/'.$client->id.'/comments')
			->with('client', $client)
			->with('comments', $comments);

		return $panel;
	}

	/*
	 * Process comment form
	 */
	public function post_comment()
	{
		$comment = new Comment;

		$comment->fill(Input::all());

		// Require a valid user
		/*if ( ! $comment->user_id = @Auth::user()->id)
		{
			return Response::error(404, array('You must be logged in to post a comment.'));
		}*/

		// And a valid Client ID
		if ( ! $client = Client::find($comment->client_id))
		{
			return Response::error(400, 'Invalid Client ID');
		}

		// Validate
		/*
		if ( ! $success = $comment->validate())
		{
			return Response::error('400', array(
				'errors' => $comment->validation_errors()
			));
		}
		*/

		// Save
		if ( ! $success = $comment->save())
		{
			return Response::error('500', 'Error saving to database');
		}

		// Ajax response
		if (Request::ajax())
		{
			$data = array(
				'status' => 'success',
				'message' => 'Saved comment successfully',
				'reload_panel' => 'entity_comments',
			);
			return Response::make(json_encode($data));
		}

		// HTML response
		Session::flash('success', 'Saved comment successfully');

		// Redirect back to whence you came
		$redirect = Input::get('redirect_uri', Request::server('http_referrer'));
		return Redirect::to($redirect);
	}
	
}