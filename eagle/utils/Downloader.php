<?php

class Downloader
{

	public static function downloadFromUrl($url)
	{
		$data = file_get_contents($url);
		return json_decode($data);
	}

	public static function getFromTba($urlCore)
	{
		$urlStart = 'https://thebluealliance.com/api/v2/';
		$urlEnd   = '?X-TBA-App-Id=frc2410:scouting-ryan:v0.0.1';
		return self::downloadFromUrl($urlStart . $urlCore . $urlEnd);
	}

	public static function getTeam($teamId) 
	{
		require_once './eagle/models/Team.php';

		$urlCore  = 'team/frc' . $teamId;
		$teamData = self::getFromTba($urlCore);
		$teamEventsData = self::getFromTba($urlCore . '/events');
		

		$events = '';
		foreach ($teamEventsData as $event) {
			$events .= $event->key . '|';
		}

		$events = trim($events, '|');

		$team = new Team;
		$team->number = $teamId;
		$team->nickname = $teamData->nickname;
		$team->events = $events;
		$team->save();
	}

	public static function getEvent($eventId)
	{
		require_once './eagle/models/Event.php';

		$urlCore  = 'event/' . $eventId;
		$eventData = self::getFromTba($urlCore);

		$event = new Event;
		$event->event_key = $eventId;
		$event->name = $eventData->name;
		$event->start_date = $eventData->start_date;
		$event->end_date   = $eventData->end_date;
		$event->save();
	}

	public static function getTeamsAtEvent($eventId)
	{
		require_once './eagle/models/Event.php';
		require_once './eagle/models/TeamEvent.php';

		$urlCore  = 'event/' . $eventId . '/teams';
		$teamsAtEvent = self::getFromTba($urlCore);

		var_dump($teamsAtEvent);
		die();

		foreach ($teamsAtEvent as $team)
		{
			$teamEvent = new TeamEvent;
			$teamEvent->event_key   = $eventId;
			$teamEvent->team_number = $team->team_number;
			$teamEvent->save();
		}
	}

}