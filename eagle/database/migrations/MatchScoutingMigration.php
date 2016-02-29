<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class MatchScoutingMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('match_scoutings');
		Capsule::schema()->create('match_scoutings', function ($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('author');
			$table->integer('team_id');
			$table->string('match_id');
			$table->integer('driver_skill');
			$table->integer('robot_speed');
			$table->integer('manueverability');
			$table->integer('penalties');
			$table->integer('helpfulness');
			$table->integer('work_well_with_us');
			$table->integer('notes_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('match_scoutings');
	}
}