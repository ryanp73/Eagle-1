<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

require_once './eagle/models/Event.php';

class Team extends Eloquent
{

	public function getEvents()
	{
		$events = explode('|', $this->events);
		
		for ($i = 0; $i < count($events); $i++)
		{
			if (!Event::where('event_key', $events[$i])->first())
			{
				Downloader::getEvent($events[$i]);
				header('Refresh:0');
			}

			$events[$i] = Event::where('event_key', $events[$i])->first();
		}

		return $events;
	}

	public function getComments()
	{
		require_once './eagle/models/Comment.php';
		return $this->hasMany('Comment');
	}

}