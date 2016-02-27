<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class PitScoutingMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('pit_scoutings');
		Capsule::schema()->create('pit_scoutings', function ($table) {
			$table->increments('id');
			$table->integer('team_id');
			$table->integer('user_id');
			$table->string('event_id');
			$table->string('author');
			$table->string('boulders');
			$table->string('num_boulders');
			$table->integer('hanging');
			$table->string('sensors');
			$table->integer('defensive_play');
			$table->integer('approx_points');
			$table->integer('approx_cycles');
			$table->text('autonomous_notes');
			$table->integer('num_drivers');
			$table->string('drivetrain');
			$table->integer('defenses_id');
			$table->integer('notes_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('pit_scoutings');
	}
}