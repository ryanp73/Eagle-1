<?php

require_once './eagle/models/Event.php';
require_once './eagle/models/TeamEvent.php';
require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/Auth.php';

$app->group('/event', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/event/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{event:\d{4}[A-Za-z]{1,4}}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();

		$event = Event::where('event_key', $args['event'])->first();
		if (!$event)
		{
			Downloader::getEvent($args['event']);
			header('Refresh:0');
		}

		$teams = TeamEvent::where('event_key', $event->event_key)->get();

		if (!count($teams))
		{
			Downloader::getTeamsAtEvent($args['event']);
			header('Refresh:0');
		}

		for ($i = 0; $i < count($teams); $i++)
		{
			if (!Team::where('number', $teams[$i]->team_number)->first())
			{
				Downloader::getTeam($teams[$i]->team_number);
				header('Refresh:0');
			}
			$teams[$i] = Team::where('number', $teams[$i]->team_number)->first();
		}

		$this->view->render($res, 'event.html', [
			'title' => $event->name,
			'event' => $event,
			'teams' => $teams,
			'user'  => Auth::getLoggedInUser()
		]);
	});
});