<?php

class FileReader
{

	public static function readFile($filename) 
	{
		if (file_exists($filename))
		{
			return json_decode(file_get_contents($filename));
		}
		else 
		{
			return false;
		}
	}

	public static function getTeam($teamId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getTeam($teamId);
		}

		$filename = './data/' . $teamId . '/basic-info.json';
		return self::readFile($filename);
	}

	public static function getEventsForTeam($teamId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getEventsForTeam($teamId);
		}

		$filename = './data/' . $teamId . '/events.json';
		return self::readFile($filename);
	}

	public static function getEvent($eventId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getEvent($eventId);
		}

		$filename = './data/' . $eventId . '/basic-info.json';
		return self::readFile($filename);
	}

	public static function getTeamsAtEvent($eventId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getTeamsAtEvent($eventId);
		}

		$filename = './data/' . $eventId . '/teams.json';
		return self::readFile($filename);
	}

	public static function getMatchesAtEvent($eventId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getMatchesAtEvent($eventId);
		}

		$filename = './data/' . $eventId . '/matches.json';
		return self::readFile($filename);
	}

	public static function getStatsAtEvent($eventId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getStatsAtEvent($eventId);
		}

		$filename = './data/' . $eventId . '/stats.json';
		return self::readFile($filename);
	}

	public static function getRankingsAtEvent($eventId, $update = false)
	{
		if ($update)
		{
			require_once './eagle/utils/Downloader.php';
			Downloader::getRankingsAtEvent($eventId);
		}

		$filename = './data/' . $eventId . '/rankings.json';
		return self::readFile($filename);
	}

}