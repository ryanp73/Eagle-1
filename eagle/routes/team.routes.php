<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Auth.php';
require_once './eagle/utils/Utils.php';
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

		$pastEvents = array();
		$futureEvents = array();
		$types = array('f' => 'Finals', 'sf' => 'Semifinals', 'qf' => 'Quarter Final', 'qm' => 'Qualifier');

		$matches = array();
		$awards = array();
		$stats = array();
		$rankings = array();

		foreach ($events as $event)
		{
			if (Utils::isAfterNow($event->end_date))
			{
				array_push($pastEvents, $event);
				$ms = FileReader::getMatchesForTeam($team->team_number, $event->key);
				$as = FileReader::getAwardsForTeam($team->team_number, $event->key);
				$ss = FileReader::getStatsAtEvent($event->key);	
				$rs = FileReader::getRankingsAtEvent($event->key);

				if ($ms == false && count($pastEvents))
				{
					foreach ($pastEvents as $event)
					{			
						Downloader::getMatchesForTeam($team->team_number, $event->key);
						header('Refresh:0');
						exit();
					}
				}		

				if ($as == false && count($pastEvents))
				{
					foreach ($pastEvents as $event)
					{			
						Downloader::getAwardsForTeam($team->team_number, $event->key);
						header('Refresh:0');
						exit();
					}
				}

				if ($ss == false && count($pastEvents))
				{
					foreach ($pastEvents as $event)
					{			
						Downloader::getStatsAtEvent($team->team_number, $event->key);
						header('Refresh:0');
						exit();
					}
				}

				if ($rs == false && count($pastEvents))
				{
					foreach ($pastEvents as $event)
					{			
						Downloader::getStatsAtEvent($team->team_number, $event->key);
						header('Refresh:0');
						exit();
					}
				}

				foreach ($ms as $m) 
				{
					$m->match_type = $types[$m->comp_level];
				}
				foreach ($rs as $ranking) 
				{
					if ($ranking[1] == $team->team_number) 
					{
						array_push($rankings, $ranking);
						break;		
					}
				}
				$s['opr'] = $ss->oprs->{$team->team_number};
				$s['dpr'] = $ss->dprs->{$team->team_number};
				$s['ccwm'] = $ss->ccwms->{$team->team_number};
				array_push($matches, $ms);
				array_push($awards, $as);
				array_push($stats, $s);
			}
			else
			{
				array_push($futureEvents, $event);
			}
		}

		$comments = Comment::where('team_id', $team->team_number);

		$defense = Defense::where('team_id', $args['team'])->orderBy('id', 'desc')->first();
		$defenses = array();
		$author = $defense ? $defense->author : false;

		if ($defense)
		{
			$defenses['Low Bar'] = $defense->low_bar;
			$defenses['Portcullis'] = $defense->portcullis;
			$defenses['Cheval de Frise'] = $defense->cheval_de_frise;
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
			'events' => $futureEvents,
			'pastEvents' => $pastEvents,
			'matches' => $matches,
			'stats' => $stats,
			'awards' => $awards,
			'rankings' => $rankings,
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