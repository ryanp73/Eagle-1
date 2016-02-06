<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class EventMigration
{
	public function up()
	{
		Capsule::schema()->dropIfExists('events');
		Capsule::schema()->create('events', function ($table) {
			$table->increments('id');
			$table->string('event_key');
			$table->date('start_date');
			$table->date('end_date');
			$table->string('name');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}