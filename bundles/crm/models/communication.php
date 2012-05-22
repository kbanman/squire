<?php namespace Crm; use \DateTime;

class Communication extends \Squire_Model {

	const TYPE_CALL  = 1;
	const TYPE_EMAIL = 2;
	const TYPE_MAIL  = 4;
	const TYPE_FAX   = 8;
	
	const DIR_IN     = 1;
	const DIR_OUT    = 2;

	public static $table = 'communications';

	public static $timestamps = true;
	
	public static $_properties = array(
		'id' => array(
			'label' => 'ID',
			'form' => false,
			'display' => false,
		),
		'created_at' => array(
			'label' => 'Date',
			'form' => false,
			'display' => false,
			'accessible' => false,
		),
		'updated_at' => array(
			'label' => 'Last Updated',
			'form' => false,
			'display' => false,
			'accessible' => false,
		),
		'datetime' => array(
			'label'   => 'Date/Time',
			'form'      => array(
				'type' => 'text',
				'field_attr' => array(
					'data-validation' => array(
						'required' => true,
						'regex' => '/^\d{4}-\d{2}-\d{2} \d{1,2}:\d{2} [aApP][mM]$/',
					),
					'data-datetime' => array(
						'ampm' => true,
						'stepMinute' => 15,
						'dateFormat' => 'yy-mm-dd',
						'timeFormat' => 'h:mm tt',
					),
				),
			),
			'validation' => array(
				'required',
				'datetime', // Squire-specific validation rule
			),
			'display' => null,
		),
		'client_id' => array(
			'form'      => array('type' => 'hidden'),
			'display'   => false,
			'validation' => array('required'),
		),
		'user_id' => array(
			'label'     => 'User',
			'form'      => false,
			'display'   => null,
			'validation' => array('required'),
		),
		'direction_id' => array(
			'label'     => 'Direction',
			'form'      => array(
				'type' => 'select',
				'options' => array(
					Communication::DIR_IN  => 'Incoming',
					Communication::DIR_OUT => 'Outgoing',
				),
			),
			'display' => false,
		),
		'type_id' => array(
			'label'     => 'Type',
			'form'      => array(
				'type' => 'select',
				'options' => array(
					Communication::TYPE_CALL  => 'Call',
					Communication::TYPE_EMAIL => 'Email',
					Communication::TYPE_MAIL  => 'Mail',
					Communication::TYPE_FAX   => 'Fax',
				),
			),
			'display' => false,
		),
		// This is a dynamic field (not actually in the table)
		'type' => array(
			'label' => 'Type',
			'form'  => false,
			'display' => null,
			'accessible' => false,
		),
		'notes' => array(
			'label'     => 'Notes',
			'form'      => array(
				'type' => 'textarea',
			),
		),
	);

	public function __construct()
	{
		static::$_properties['datetime']['display'] = function($comm)
		{
			// TODO: This is ugly
			// but the DateTime constructor doesn't allow for timestamps
			// Also, trying that results in an uncatchable Exception. PHP :(
			if (is_int($comm->datetime))
			{
				return date('d-M-Y g:i a', $comm->datetime);
			}

			try
			{
				$date = new DateTime($comm->datetime);
			}
			catch (\Exception $e)
			{
				return 'Bad time format';
			}

			return $date->format('d-M-Y g:i a');
		};

		static::$_properties['user_id']['display'] = function($comm)
		{
			return $comm->user->name;
		};

		static::$_properties['type']['display'] = function($comm)
		{
			return $comm->value('direction_id').' '.$comm->value('type_id');
		};

		static::$_properties['notes']['display'] = function($comm)
		{
			return nl2br($comm->notes);
		};

		parent::__construct();
	}

	public function client()
	{
		return $this->belongs_to('\\Crm\\Client');
	}

	public function user()
	{
		return $this->belongs_to('\\Squire_User');
	}

	public static function columns_panel()
	{
		return static::table_columns(null, array(
			'datetime', 'user_id', 'type', 'notes'
		));
	}

	public function save()
	{
		// Convert the datetime to timestamp
		if ( ! is_a($this->datetime, 'DateTime'))
		{
			$this->datetime = new DateTime($this->datetime);
		}

		return parent::save();
	}
}