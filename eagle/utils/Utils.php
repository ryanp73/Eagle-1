<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/models/PitScouting.php';

class Utils
{

	public static function getUnscoutedTeams($event) 
	{
		$teams = array();
		foreach (FileReader::getTeamsAtEvent($event) as $team) 
		{
			if (!PitScouting::where(array('team_id' => $team->team_number, 'event_id' => $event))->first())
			{
				array_push($teams, $team);
			}
		}
		return $teams;
	}

	public static function getCurrentEvent() 
	{
		$pivot = strtotime('2016-03-14');
		if (time() < $pivot) 
		{
			return '2016mokc';
		} 
		else
		{
			return '2016iacf';
		}
	}

}