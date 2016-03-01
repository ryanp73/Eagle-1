<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class DefenseMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('defenses');
		Capsule::schema()->create('defenses', function ($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('author');
			$table->integer('team_id');
			$table->integer('low_bar');
			$table->integer('portcullis');
			$table->integer('cheval_de_frise');
			$table->integer('moat');
			$table->integer('ramparts');
			$table->integer('drawbridge');
			$table->integer('sally_port');
			$table->integer('rock_wall');
			$table->integer('rough_terrain');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('defenses');
	}
}