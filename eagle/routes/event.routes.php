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

	    if (strtotime($event->start_date) <= time()) 
	    {

	        $matches = FileReader::getMatchesAtEvent($args['event']);

	        if (!count($matches))
	        {
					Downloader::getMatchesAtEvent($args['event']);
	                header('Refresh:0');
	        }

	        $types = array('f' => 'Finals', 'sf' => 'Semifinals', 'qf' => 'Quarter Final', 'qm' => 'Qualifier');

	        foreach ($matches as $match) 
	        {
				$match->match_type = $types[$match->comp_level];
	        }

	        $rankings = FileReader::getRankingsAtEvent($args['event']);

	        if (!count($rankings))
	        {
				Downloader::getRankingsAtEvent($args['event']);
				header('Refresh:0');
	        }

	        $dlstats = FileReader::getStatsAtEvent($args['event']);

	        if (!$dlstats && !count($dlstats))
	        {
	            Downloader::getStatsAtEvent($args['event']);
	            header('Refresh:0');
	        }

	        $stats = array();

	        for ($i = 0; $i < count((array)$dlstats->oprs); $i++)
	        {
				$tempTeam = array_keys((array)$dlstats->oprs)[$i];
				$opr = $dlstats->oprs->{$tempTeam};
				$dpr = $dlstats->dprs->{$tempTeam};
				$ccwm = $dlstats->ccwms->{$tempTeam};
				$stats[$tempTeam] = array('number' => $tempTeam, 'opr' => $opr, 'dpr' => $dpr, 'ccwm' => $ccwm);
            }
        }

		$this->view->render($res, 'event.html', [
			'title' => $event->name,
			'event' => $event,
			'teams' => $teams,
			'stats' => $stats,
			'rankings' => $rankings,
			'matches' => $matches,
			'user'  => Auth::getLoggedInUser()
		]);
	});

	$this->get('/{event:\d{4}[A-Za-z]{1,4}}/update', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		FileReader::getEvent($args['event'], true);
		FileReader::getTeamsAtEvent($args['event'], true);
		FileReader::getMatchesAtEvent($args['event'], true);
		FileReader::getStatsAtEvent($args['event'], true);
		FileReader::getRankingsAtEvent($args['event'], true);
		return $res->withStatus(302)->withHeader('Location', '/event/' . $args['event']);
	});

});