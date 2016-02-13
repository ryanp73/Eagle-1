<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Auth.php';
require_once './eagle/models/Comment.php';
require_once './eagle/models/Defense.php';
require_once './eagle/models/PitScouting.php';

$app->group('/team', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/team/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{team:[0-9]{1,4}}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();

		$team = FileReader::getTeam($args['team']);

		if (!$team) 
		{
			Downloader::getTeam($args['team']);
			header('Refresh:0');	
		}

		$events = FileReader::getEventsForTeam($args['team']);

		if (!$events) 
		{
			Downloader::getEventsForTeam($args['team']);
			header('Refresh:0');
		}

		$comments = Comment::where('team_id', $team->team_number);

		$defense = Defense::where('team_id', $args['team'])->orderBy('id', 'desc')->first();
		$defenses = array();
		$author = $defense ? $defense->author : false;

		if ($defense)
		{
			$defenses['Low Bar'] = $defense->low_bar;
			$defenses['Portcullis'] = $defense->portcullis;
			$defenses['Cheval de Frsie'] = $defense->cheval_de_frise;
			$defenses['Moat'] = $defense->moat;
			$defenses['Ramparts'] = $defense->ramparts;
			$defenses['Drawbridge'] = $defense->drawbridge;
			$defenses['Sally Port'] = $defense->sally_port;
			$defenses['Rock Wall'] = $defense->rock_wall;
			$defenses['Rough Terrain'] = $defense->rough_terrain;
		}
		

		return $this->view->render($res, 'team.html', [
			'title'  => 'Team ' . $team->team_number,
			'team'   => $team,
			'events' => $events,
			'numComments' => Comment::where('team_id', $team->team_number)->count(),
			'comment' => Comment::where('team_id', $team->team_number)->first(),
			'defenseExists' => $defense,
			'defenses' => $defenses,
			'pitScoutingData' => PitScouting::where('team_id', $team->team_number)->first(),
			'author' => $author,
			'comments' => $comments,
			'user'   => Auth::getLoggedInUser()
		]);
	});
});