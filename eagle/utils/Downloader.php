<?php

class Downloader
{

	public static function downloadFromUrl($url)
	{
		$data = file_get_contents($url);
		return $data;
	}

	public static function getFromTba($urlCore)
	{
		$urlStart = 'http://thebluealliance.com/api/v2/';
        $urlEnd   = '?X-TBA-App-Id=frc2410:scouting-ryan:v1.0.0';
		return self::downloadFromUrl($urlStart . $urlCore . $urlEnd);
	}

	public static function writeDataToFile($filename, $data) 
	{
		if (!file_exists(dirname($filename)))
		{
			mkdir(dirname($filename));
		}

		$file = fopen($filename, 'w');
		fwrite($file, $data);
		fclose($file);
	}

	public static function getTeam($teamId) 
	{
		$urlCore  = 'team/frc' . $teamId;
		$team = self::getFromTba($urlCore);

		$filename = './data/' . $teamId . '/basic-info.json';

		self::writeDataToFile($filename, $team);

		self::getEventsForTeam($teamId);
	}

	public static function getEventsForTeam($teamId) 
	{
		$urlCore  = 'team/frc' . $teamId . '/events';
		$teamEvents = self::getFromTba($urlCore);

		$filename = './data/' . $teamId . '/events.json';

		self::writeDataToFile($filename, $teamEvents);
	}

	public static function getEvent($eventId)
	{
		$urlCore  = 'event/' . $eventId;
		$event = self::getFromTba($urlCore);

		$filename = './data/' . $eventId . '/basic-info.json';

		self::writeDataToFile($filename, $event);

		self::getTeamsAtEvent($eventId);
	}

	public static function getTeamsAtEvent($eventId)
	{
		$urlCore  = 'event/' . $eventId . '/teams';
		$teamsAtEvent = self::getFromTba($urlCore);

		$filename = './data/' . $eventId . '/teams.json';

		self::writeDataToFile($filename, $teamsAtEvent);
	}

	public static function getMatchesAtEvent($eventId)
	{
		$urlCore  = 'event/' . $eventId . '/matches';
		$teamsAtEvent = self::getFromTba($urlCore);

		$filename = './data/' . $eventId . '/matches.json';

		self::writeDataToFile($filename, $teamsAtEvent);
	}

	public static function getStatsAtEvent($eventId)
	{
		$urlCore  = 'event/' . $eventId . '/stats';
		$teamsAtEvent = self::getFromTba($urlCore);

		$filename = './data/' . $eventId . '/stats.json';

		self::writeDataToFile($filename, $teamsAtEvent);
	}

	public static function getRankingsAtEvent($eventId)
	{
		$urlCore  = 'event/' . $eventId . '/rankings';
		$teamsAtEvent = self::getFromTba($urlCore);

		$filename = './data/' . $eventId . '/rankings.json';

		self::writeDataToFile($filename, $teamsAtEvent);
	}

}
