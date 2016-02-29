<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class CommentMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('comments');
		Capsule::schema()->create('comments', function ($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('author');
			$table->integer('team_id');
			$table->text('notes');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('comments');
	}
}