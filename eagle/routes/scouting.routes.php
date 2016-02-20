<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/models/PitScouting.php';
require_once './eagle/models/Defense.php';
require_once './eagle/models/MatchScouting.php';
require_once './eagle/models/Comment.php';

$app->group('/scouting', function()  use ($app) {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/event/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/pit/new', function($req, $res, $args) {
		$this->view->render($res, 'pitScouting.html', [
			'title' => 'New Pit Scouting',
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