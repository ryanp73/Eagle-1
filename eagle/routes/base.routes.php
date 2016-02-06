<?php

require_once './eagle/utils/Auth.php';

$app->get('/', function($req, $res, $args) {
	$this->view->render($res, 'home.html', [
		'title' => 'Eagle Home',
		'loggedIn' => Auth::checkLoggedIn(),
		'user'  => Auth::getLoggedInUser()
	]);
});

$app->get('/failure', function($req, $res, $args) {
	$this->view->render($res, 'failure.html', [
		'title' => 'Failure'
	]);
});

$app->post('/login', function($req, $res, $args) {
	if (Auth::login($_POST['email'], $_POST['password']))
	{	
		return $res->withStatus(302)->withHeader('Location', '/');
	}
	else
	{
		return $res->withStatus(302)->withHeader('Location', '/failure');
	}
});

$app->get('/logout', function($req, $res, $args) {
	Auth::logout();
	return $res->withStatus(302)->withHeader('Location', '/');
});