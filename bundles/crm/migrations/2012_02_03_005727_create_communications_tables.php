<?php

class Communications_Create_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('communications', function($table)
		{
			$table->create();

			$table->increments('id');
			$table->timestamps();
			$table->timestamp('datetime');
			$table->string('user_id');
			$table->string('client_id');
			$table->integer('type_id');
			$table->integer('direction_id');
			$table->text('notes');

			$table->index('client__id', 'communcations_for_client');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('communications', function($table)
		{
			$table->drop();
		});
	}

}