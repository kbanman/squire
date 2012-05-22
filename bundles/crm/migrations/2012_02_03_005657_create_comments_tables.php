<?php

class Crm_Create_Comments_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function($table)
		{
			$table->create();

			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id');
			$table->integer('client_id');
			$table->text('comment');

			$table->index('client_id', 'comments_for_client');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function($table)
		{
			$table->drop();
		});
	}

}