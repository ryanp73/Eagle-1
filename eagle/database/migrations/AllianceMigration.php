<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class AllianceMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('comments');
		Capsule::schema()->create('comments', function ($table) {
			$table->increments('id');
			$table->string('event_id');
			$table->integer('rank');
			$table->integer('team1_id');
			$table->integer('team2_id');
			$table->integer('team3_id');
			$table->string('notes');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('comments');
	}
}