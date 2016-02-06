<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class TeamMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('teams');
		Capsule::schema()->create('teams', function ($table) {
			$table->increments('id');
			$table->integer('number');
			$table->string('nickname');
			$table->string('events');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('teams');
	}
}