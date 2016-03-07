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
		return (self::isBeforeNow('2016-03-14')) ? '2016mokc' : '2016iacf';
	}

	public static function isEventOccuring($event)
	{
		return self::isAfterNow($event->start_date)
				&& self::isBeforeNow(self::addADay($event->end_date));
	}

	public static function hasEventPast($event)
	{
		return self::isAfterNow(self::addADay($event->end_date));
	}

	public static function isBefore($date1, $date2)
	{
		return strtotime($date1) < strtotime($date2);
	}

	public static function isBeforeNow($date)
	{
		return strtotime($date) > time();
	}

	public static function isAfterNow($date)
	{
		return strtotime($date) < time();
	}

	public static function isAfter($date1, $date2)
	{
		return strtotime($date1) > strtotime($date2);
	}

	public static function averageArray($array)
	{
		return array_sum($array) / count($array);
	}

	public static function addADay($date)
	{
		return strtotime($date) + 86400;
	}

}