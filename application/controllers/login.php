<?php

class Login_Controller extends Base_Controller {

	public $restful = true;

	public function get_index($errors = null)
	{
		$fields = array(
			'email',
			'password:password',
		);
		$form = Squi\Form::bootstrap($fields);

		is_null($errors) || $form->with('errors', $errors);

		Session::reflash();

		return View::of('template')
			->with('title', 'Login')
			->with('page_heading', 'Login to continue')
			->with('content', $form);
	}

	public function post_index()
	{
		if (Auth::attempt(Input::all()))
		{
			return Redirect::to(Session::get('login_redirect', '/'));
		}

		Session::reflash();

		return $this->get_index(array('password' => 'Invalid username or password'));
	}

	public function get_create()
	{
		$user = new Sentry\Sentry_User;

		try
		{
			$result = $user->register(array(
				'email' => 'admin@admin.com',
				'password' => 'password',
			));
			echo 'Created user "admin@admin.com" with password "password"';
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}

	}

	public function get_logout()
	{
		Auth::logout();

		return Redirect::to('/');
	}

}