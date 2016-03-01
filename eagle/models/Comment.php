<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent
{

	public function getAuthor() 
	{
		require_once './eagle/models/User.php';
		return $this->belongsTo('User');
	}

}