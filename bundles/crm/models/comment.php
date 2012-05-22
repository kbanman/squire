<?php namespace Crm;

class Comment extends \Squire_Model {

	public static $table = 'comments';

	public static $timestamps = true;
	
	public static $_properties = array(
		'id' => array(
			'label' => 'ID',
			'display' => false,
			'form' => false,
		),
		'created_at' => array(
			'label' => 'Date',
			'form' => false,
		),
		'updated_at' => array(
			'label' => 'Last Updated',
			'display' => false,
			'form' => false,
		),
		'user_id' => array(
			'display' => false,
			'form' => false,
			'validation' => array('required'),
		),
		'client_id' => array(
			'display' => false,
			'form' => false,
			'validation' => array('required'),
		),
		'comment' => array(
			'label' => 'Comment',
			'form' => array(
				'type' => 'textarea',
			),
			'validation' => array('required'),
		),
	);

	public function client()
	{
		return $this->belongs_to('\\Crm\\Client');
	}

	public function user()
	{
		return $this->belongs_to('\\Squire_User');
	}
}