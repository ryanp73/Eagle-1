<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Auth.php';

$app->group('/event', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/event/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{event:\d{4}[A-Za-z]{1,4}}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();

		$event = FileReader::getEvent($args['event']);
		if (!$event)
		{
			Downloader::getEvent($args['event']);
			header('Refresh:0');
		}

		$teams = FileReader::getTeamsAtEvent($args['event']);

		if (!count($teams))
		{
			Downloader::getTeamsAtEvent($args['event']);
			header('Refresh:0');
		}

		$this->view->render($res, 'event.html', [
			'title' => $event->name,
			'event' => $event,
			'teams' => $teams,
			'user'  => Auth::getLoggedInUser()
		]);
	});
});