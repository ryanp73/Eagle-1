<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class TeamEventMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('team_events');
		Capsule::schema()->create('team_events', function ($table) {
			$table->increments('id');
			$table->integer('team_number');
			$table->string('event_key');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('team_events');
	}
}