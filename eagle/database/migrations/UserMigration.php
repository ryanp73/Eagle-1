<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class UserMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('users');
		Capsule::schema()->create('users', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->integer('rank');
			$table->timestamps();
		});
	}

	public function down()
	{
		Capsule::schema()->drop('users');
	}
}