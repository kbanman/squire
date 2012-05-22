<?php

class Comments_Create_Tables {

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
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->string('user_id');
			$table->string('customer_id');
			$table->text('comment');

			$table->index('customer_id', 'comments_by_customer');
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