<?php

class Squire_User extends Squire_Model {

	public static $primary_key = 'user_id';
	
	public static $_properties = array(
		'id' => array(
			'label' => 'ID',
			'display' => false,
			'form' => false,
		),
		'created_at' => array(
			'label' => 'Creation Date',
			'display' => false,
			'form' => false,
		),
		'updated_at' => array(
			'label' => 'Last Updated',
			'display' => false,
			'form' => false,
		),
		'name' => array(
			'label' => 'Name',
		),
	);

	
}