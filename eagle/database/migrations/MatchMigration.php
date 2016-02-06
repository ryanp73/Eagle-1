<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class MatchMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('matches');
		Capsule::schema()->create('matches', function ($table) {
			$table->increments('id');
			$table->string('event_id');
			$table->integer('blue_score');
			$table->integer('blue_team1_number');
			$table->integer('blue_team2_number');
			$table->integer('blue_team3_number');
			$table->integer('red_score');
			$table->integer('red_team1_number');
			$table->integer('red_team2_number');
			$table->integer('red_team3_number');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('matches');
	}
}