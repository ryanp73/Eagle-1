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
			$table->string('driver_skill');
			$table->string('robot_speed');
			$table->string('manueverability');
			$table->string('penalties');
			$table->string('helpfulness');
			$table->string('work_well_with_us');
			$table->text('notes');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('match_scoutings');
	}
}