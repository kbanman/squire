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
			->where('client_id', '=', $client->id)
			->order_by('created_at', 'desc')
			->get();

		$panel = View::make('crm::comments')
			->with('id', 'client_comments')
			->with('uri', 'clients/'.$client->id.'/comments')
			->with('client', $client)
			->with('comments', $comments);

		return $panel;
	}

	/*
	 * Process comment form
	 */
	public function post_comment()
	{
		// Detect the referrer for non-ajax redirection
		$redirect = Input::get('redirect_uri', Request::server('http_referer'));

		$comment = new Comment;

		$comment->fill(Input::all());

		// Require a valid user
		if ( ! $comment->user_id = Auth::user()->id)
		{
			return Response::error(401, array('You must be logged in to post a comment.'));
		}

		// And a valid Client ID
		if ( ! $client = Client::find($comment->client_id))
		{
			return Response::error(400, 'Invalid Client ID');
		}

		// Validate
		if ( ! $comment->validate())
		{
			if (Request::ajax())
			{
				return Response::make(json_encode(array(
					'status' => 'error',
					'errors' => $comment->validation_errors(),
				)), 400);
			}

			return Redirect::to($redirect)->with('errors', $comment->validation_errors());
		}

		// Save
		if ( ! $comment->save())
		{
			if (Request::ajax())
			{
				return Response::make(json_encode(array(
					'status' => 'error',
					'errors' => 'Error saving to database',
				)), 500);
			}

			return Redirect::to($redirect)->with('errors', array('Error saving to database'));
		}

		// Success
		if (Request::ajax())
		{
			$data = array(
				'status' => 'success',
				'message' => 'Saved comment successfully',
				'reload_panel' => 'client_comments',
				'comment' => $comment->to_array(),
				'user' => $comment->to_array(),
			);
			return Response::make(json_encode($data));
		}

		// HTML response
		return Redirect::to($redirect)->with('message', 'Saved comment successfully');
	}
	
}