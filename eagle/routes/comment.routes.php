<?php

require_once './eagle/utils/Auth.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/models/Comment.php';

$app->group('/comment', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/event/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/new', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$this->view->render($res, 'newComment.html', [
			'title' => 'New Comment',
			'user'  => Auth::getLoggedInUser()
		]);
	});

	$this->post('/new', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$comment = new Comment();
		$comment->user_id = Auth::getLoggedInUser()->id;
		$comment->author = Auth::getLoggedInUser()->name;
		$comment->team_id = $_POST['teamId'];
		$comment->notes = $_POST['notes'];
		$comment->save();
		header('Location:/comment/' . $_POST['teamId']);
		exit();
	});

	$this->get('/{team:[0-9]{1,4}}', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$comments = Comment::where('team_id', $args['team'])->get();
		$title = 'Comments for ' . $args['team'];
		$this->view->render($res, 'teamComments.html', [
			'title' => $title,
			'comments' => $comments,
			'team'  => FileReader::getTeam($args['team']),
			'user'  => Auth::getLoggedInUser(),
		]);
	});

});