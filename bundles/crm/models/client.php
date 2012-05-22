<?php namespace Crm;

class Client extends \Squire_Model {

	public static $per_page = 15;

	public static $table = 'clients';
	
	public static $_properties = array(
		'id' => array(
			'label' => 'Client ID',
			'display' => false,
			'form' => false,
		),
		'created_on' => array(
			'label' => 'Creation Date',
			'display' => false,
			'form' => false,
		),
		'updated_on' => array(
			'label' => 'Last Updated',
			'display' => false,
			'form' => false,
		),
		'type' => array(
			'label' => 'Type',
			'display' => false,
			'form' => false,
		),
		'business_name' => array(
			'label' => 'Name',
			'weight' => 'primary',
		),
		'contact' => array(
			'label' => 'Contact',
			'weight' => 'important',
		),
		'address_street' => array(
			'label' => 'Address',
			'weight' => 'slightly-important',
		),
		'address_street2' => array(
			'label' => 'Address 2',
			'weight' => 'slightly-important',
		),
		'address_city' => array(
			'label' => 'City',
			'weight' => 'important',
		),
		'address_province' => array(
			'label' => 'Province',
			'weight' => 'important',
		),
		'address_country' => array(
			'label' => 'Country',
			'display' => false,
			'weight' => 'not-important',
		),
		'address_postalcode' => array(
			'label' => 'Postal Code',
			'display' => false,
			'weight' => 'not-important',
		),
		'phone_main' => array(
			'label' => 'Phone',
			'weight' => 'very-important',
		),
		'phone_main_ext' => array(
			'label' => 'Phone Ext.',
			'weight' => 'important',
		),
		'phone_other' => array(
			'label' => 'Other Phone',
			'weight' => 'slightly-important'
		),
		'phone_fax' => array(
			'label' => 'Fax Number',
			'weight' => 'not-important'
		),
		'email' => array(
			'label' => 'Email',
			'weight' => 'fairly-important',
		),
		'notes' => array(
			'label' => 'Notes',
			'weight' => 'not-important',
		),
	);

	public static function columns_overview()
	{
		return static::table_columns(array(
			'business_name',
			'Address' => function($client)
			{
				$address = $client->address_street;
				! empty($client->address_street2) and $address .= ', '.$client->address_street2;
				return $address;
			},
			'address_city',
			'phone_main',
		));
	}

	public function name()
	{
		if (empty($this->business_name))
		{
			return $this->contact;
		}
		return $this->business_name;
	}

}