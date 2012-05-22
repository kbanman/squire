<?php

class Crm_Create_Clients_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clients', function($table)
		{
			$table->create();

			$table->increments('id');
			$table->timestamps();
			$table->string('type', 16);
			$table->string('business_name', 127);
			$table->string('phone_main', 12);
			$table->string('phone_main_ext', 16);
			$table->string('phone_other', 12);
			$table->string('phone_fax', 12);
			$table->string('email', 127);
			$table->string('address_street', 127);
			$table->string('address_street_2', 127);
			$table->string('address_city', 127);
			$table->string('address_province', 64);
			$table->string('address_postalcode', 12);
			$table->string('address_country', 64);
			$table->text('notes');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clients', function($table)
		{
			$table->drop();
		});
	}

}