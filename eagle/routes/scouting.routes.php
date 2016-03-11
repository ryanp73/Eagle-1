<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Utils.php';
require_once './eagle/models/PitScouting.php';
require_once './eagle/models/Defense.php';
require_once './eagle/models/MatchScouting.php';
require_once './eagle/models/Comment.php';

$app->group('/scouting', function()  use ($app) {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/event/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/pit', function($req, $res, $args) {
		Auth::getLoggedInUser();
		$event = Utils::getCurrentEvent();
		$this->view->render($res, 'pitScoutingHome.html', [
			'title' => 'Pit Scouting Home',
			'teams' => Utils::getUnscoutedTeams($event),
			'event' => FileReader::getEvent($event),
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->get('/pit/new/{team:[0-9]{1,4}}', function($req, $res, $args) {
		Auth::getLoggedInUser();
		$this->view->render($res, 'pitScouting.html', [
			'title' => 'Pit Scouting for ' . $args['team'],
			'team' => $args['team'],
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->post('/pit/new', function($req, $res, $args) {
		$defense = new Defense();
		$defense->user_id = Auth::getLoggedInUser()->id;
		$defense->author = Auth::getLoggedInUser()->name;
		$defense->team_id = $_POST['team_id'];
		$defense->low_bar = $_POST['lowbar'];
		$defense->portcullis = $_POST['portcullis'];
		$defense->cheval_de_frise = $_POST['cheval'];
		$defense->moat = $_POST['moat'];
		$defense->ramparts = $_POST['ramparts'];
		$defense->drawbridge = $_POST['drawbridge'];
		$defense->sally_port = $_POST['sallyport'];
		$defense->rock_wall = $_POST['rockwall'];
		$defense->rough_terrain = $_POST['roughterrain'];
		$defense->save();

		$comment = new Comment();
		$comment->user_id = Auth::getLoggedInUser()->id;
		$comment->author = Auth::getLoggedInUser()->name;
		$comment->team_id = $_POST['team_id'];
		$comment->notes = $_POST['notes'];
		$comment->save();

		$pit = new PitScouting();
		$pit->team_id = $_POST['team_id'];
		$pit->user_id = Auth::getLoggedInUser()->id;
		$pit->author = Auth::getLoggedInUser()->name;
		$pit->event_id = Utils::getCurrentEvent();
		$pit->num_boulders = $_POST['num_boulders'];
		$pit->boulders = $_POST['boulders'];
		$pit->hanging = $_POST['climbing'];
		$pit->sensors = $_POST['sensors'];
		$pit->defensive_play = $_POST['defensive_play'];
		$pit->approx_points = $_POST['approx_points'];
		$pit->approx_cycles = $_POST['approx_cycles'];
		$pit->autonomous_notes = $_POST['autonomous_notes'];
		$pit->num_drivers = $_POST['num_drivers'];
		$pit->drivetrain = $_POST['drivetrain'];
		$pit->defenses_id = $defense->id;
		$pit->notes_id = $comment->id;
		$pit->save();

		if (isset($_FILES['image']))
		{
			move_uploaded_file($_FILES['image']['tmp_name'], './img/' . $_POST['team_id']);
		}

		header('Location:/team/' . $_POST['team_id'], '/');
		exit();
	});

	$this->get('/defense/{team:[0-9]{1,4}}', function($req, $res, $args) {
		$defense = Defense::where('team_id', $args['team'])->orderBy('id', 'desc')->first();
		$defenses = array();
		$defenses['Low Bar'] = $defense->low_bar;
		$defenses['Portcullis'] = $defense->portcullis;
		$defenses['Cheval de Frsie'] = $defense->cheval_de_frise;
		$defenses['Moat'] = $defense->moat;
		$defenses['Ramparts'] = $defense->ramparts;
		$defenses['Drawbridge'] = $defense->drawbridge;
		$defenses['Sally Port'] = $defense->sally_port;
		$defenses['Rock Wall'] = $defense->rock_wall;
		$defenses['Rough Terrain'] = $defense->rough_terrain;

		foreach ($defenses as $name => $value) {
			switch ($value) {
				case 2:
					$defenses[$name] = "Can and will cross defense.";
					break;
				case 1:
					$defenses[$name] = "Can, but doesn't like to cross defense.";
					break;
				default:
					$defenses[$name] = "Can't cross defense.";
					break;
			}
		}

		$this->view->render($res, 'defense.html', [
			'title' => 'Defenses for ' . $args['team'],
			'user' => Auth::getLoggedInUser(),
			'team_number' => $defense->team_id,
			'defenses' => $defenses,
			'author' => $defense->author
		]);
	});

	$this->get('/match/new/{id:20\d{2}[a-z]{3,5}_[a-z]{1,2}\d?m\d{1,3}}', function($req, $res, $args) {		
		$this->view->render($res, 'matchScouting.html', [
			'title' => 'New Match Scouting',
			'user' => Auth::getLoggedInUser(),
			'id' => $args['id']
		]);
	});

	$this->get('/images/{team:\d{1,4}}', function($req, $res, $args) {		
		$this->view->render($res, 'imageSubmit.html', [
			'title' => 'Image for Team ' . $args['team'],
			'team' => $args['team'],
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->post('/images/{team:\d{1,4}}', function($req, $res, $args) {		
		if (isset($_FILES['image']))
		{
			move_uploaded_file($_FILES['image']['tmp_name'], './img/' . $args['team']);
		}
		return $res->withStatus(302)->withHeader('Location', '/scouting/images/list');
	});

	$this->get('/images/list', function($req, $res, $args) {		
		$teams = Utils::getUnscoutedTeams(Utils::getCurrentEvent());

		$this->view->render($res, 'imagesList.html', [
			'title' => 'List of Pictures to Take',
			'teams' => $teams,
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->get('/ds', function($req, $res, $args) {		
		$this->view->render($res, 'defenseSelector.html', [
			'title' => 'Defense Selector',
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->post('/ds', function($req, $res, $args) {
		$location = '/scouting/ds';
		$location .= '/' . $_POST['team1'];		
		$location .= '/' . $_POST['team2'];		
		$location .= '/' . $_POST['team3'];		
		return $res->withStatus(302)->withHeader('Location', $location);
	});

	$this->get('/ds/{team1:\d{1,4}}/{team2:\d{1,4}}/{team3:\d{1,4}}', function($req, $res, $args) {		
		$team1d = Defense::where('team_id', $args['team1'])->first();
		$team2d = Defense::where('team_id', $args['team2'])->first();
		$team3d = Defense::where('team_id', $args['team3'])->first();

		$selections = array();

		$portcullis = $team1d->portcullis + $team2d->portcullis + $team3d->portcullis;
		$cheval_de_frise = $team1d->cheval_de_frise + $team2d->cheval_de_frise + $team3d->cheval_de_frise;

		if ($portcullis < $cheval_de_frise) $selections['a'] = ['Portcullis', $portcullis];
		else if ($portcullis == $cheval_de_frise) $selections['a'] = ['Equal', $portcullis];
		else $selections['a'] = ['Cheval de Frise', $cheval_de_frise];

		$moat = $team1d->moat + $team2d->moat + $team3d->moat;
		$ramparts = $team1d->ramparts + $team2d->ramparts + $team3d->ramparts;

		if ($moat < $ramparts) $selections['b'] = ['Moat', $moat];
		else if ($moat == $ramparts) $selections['b'] = ['Equal', $moat];
		else $selections['b'] = ['Ramparts', $ramparts];

		$drawbridge = $team1d->drawbridge + $team2d->drawbridge + $team3d->drawbridge;
		$sally_port = $team1d->sally_port + $team2d->sally_port + $team3d->sally_port;

		if ($drawbridge < $sally_port) $selections['c'] = ['Drawbridge', $drawbridge];
		else if ($drawbridge == $sally_port) $selections['c'] = ['Equal', $drawbridge];
		else $selections['c'] = ['Sally Port', $sally_port];

		$rock_wall = $team1d->rock_wall + $team2d->rock_wall + $team3d->rock_wall;
		$rough_terrain = $team1d->rough_terrain + $team2d->rough_terrain + $team3d->rough_terrain;

		if ($rock_wall < $rough_terrain) $selections['d'] = ['Rock Wall', $rock_wall];
		else if ($rock_wall == $rough_terrain) $selections['d'] = ['Equal', $rock_wall];
		else $selections['d'] = ['Rough Terrain', $rough_terrain];

		$low_bar = $team1d->low_bar + $team2d->low_bar + $team3d->low_bar;
		
		if ($low_bar < 2) $selections['low'] = ['Inconsistent', $low_bar];
		else if ($low_bar >= 2 && $low_bar < 4) $selections['low'] = ['Probably', $low_bar];
		else if ($low_bar >= 4) $selections['low'] = ['Guaranteed', $low_bar];

		$this->view->render($res, 'defenseSelections.html', [
			'title' => 'Defense Selector',
			'selections' => $selections,
			'team1' => $args['team1'],
			'team2' => $args['team2'],
			'team3' => $args['team3'],
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->get('/match', function($req, $res, $args) {
		$this->view->render($res, 'matchScoutingHome.html', [
			'title' => 'Match Scouting',
			'user' => Auth::getLoggedInUser()
		]);
	});

	$this->post('/match', function($req, $res, $args) {
		$str = '2016';
		$str .= $_POST['competition'];
		$str .= '_';
		$str .= $_POST['matchType'];
		if ($_POST['matchType'] != 'q') $str .= '1';
		$str .= 'm';
		$str .= $_POST['matchNumber'];
		$event = split('_', $str)[0];
		Downloader::getMatchesAtEvent($event);
		return $res->withStatus(302)->withHeader('Location', '/scouting/match/new/' . $str);
	});

	$this->post('/match/new', function($req, $res, $args) {
		$comment = new Comment();
		$comment->user_id = Auth::getLoggedInUser()->id;
		$comment->author = Auth::getLoggedInUser()->name;
		$comment->team_id = $_POST['team_id'];
		$comment->notes = $_POST['notes'];
		$comment->save();

		$match = new MatchScouting();
		$match->team_id = $_POST['team_id'];
		$match->user_id = Auth::getLoggedInUser()->id;
		$match->author = Auth::getLoggedInUser()->name;
		$match->match_id = $_POST['match_id'];
		$match->driver_skill = $_POST['driver_skill'];
		$match->robot_speed = $_POST['robot_speed'];
		$match->manueverability = $_POST['manueverability'];
		$match->penalties = $_POST['penalties'];
		$match->helpfulness = $_POST['helpfulness'];
		$match->work_well_with_us = $_POST['work_well_with_us'];
		$match->notes_id = $comment->id;
		$match->save();
		header('Location:/team/' . $_POST['team_id'], '/');
		exit();
	});
	
});
