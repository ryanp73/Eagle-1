<?php

require_once './eagle/models/Team.php';
require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/Auth.php';

$app->group('/team', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/team/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{team:[0-9]{1,4}}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$team = Team::where('number', (int)$args['team'])->first();

		if (!$team) 
		{
			Downloader::getTeam($args['team']);
			header('Refresh:0');	
		}

		return $this->view->render($res, 'team.html', [
			'title'  => 'Team ' . $team->number,
			'team'   => $team,
			'events' => $team->getEvents(),
			'comments' => $team->getComments(),
			'user'   => Auth::getLoggedInUser()
		]);
	});
});