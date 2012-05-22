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
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->timestamp('datetime');
			$table->string('user_id');
			$table->string('customer_id');
			$table->integer('type_id');
			$table->integer('direction_id');
			$table->text('notes');

			$table->index('customer_id', 'communcations_by_customer');
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