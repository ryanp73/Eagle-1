<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Auth.php';
require_once './eagle/utils/Utils.php';
require_once './eagle/models/MatchScouting.php';
require_once './eagle/models/Comment.php';

$app->group('/match', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/match/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{match:\d{4}[A-Za-z]{2,5}\d?_[qsf]+\d?m\d+}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();

		$eventKey = explode('_', $args['match'])[0];
		$event = FileReader::getEvent($eventKey);

		$matches = FileReader::getMatchesAtEvent($eventKey);
		if (!$matches && Utils::hasEventPast($event))
		{
			Downloader::getMatchesAtEvent($eventKey);
			header('Refresh:0');
			exit();
		}

		for ($i = 0; $i < count($matches); $i++) {
			if ($matches[$i]->key == $args['match']) {
				$match = $matches[$i];
				break;
			}
		}

		$ms = MatchScouting::where('match_id', $args['match'])->get()->toArray();

		for ($i = 0; $i < count($ms); $i++) 
		{	
			$ms[$i]['notes'] = Comment::where('id', (int)$ms[$i]['notes_id'])->first()->toArray();
		}

		$types = array('f' => 'Finals', 'sf' => 'Semifinals', 'qf' => 'Quarter Final', 'qm' => 'Qualifier');

		$match->type = $types[$match->comp_level];
		$match->name = $event->name . ' ' . $match->type . ' ' . $match->match_number;

		$this->view->render($res, 'match.html', [
			'title' => $match->name,
			'event' => $event,
			'match' => $match,
			'matchScouting' => $ms,
			'user'  => Auth::getLoggedInUser()
		]);
	});

});