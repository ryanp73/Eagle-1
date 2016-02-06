<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class Connection 
{
	public function __construct()
	{
		$this->capsule = new Capsule;
		$this->capsule->addConnection(include './eagle/config/database.php', 'default');

		$this->capsule->bootEloquent();
		$this->capsule->setAsGlobal();
	}
}