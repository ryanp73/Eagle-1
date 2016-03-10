<?php

require_once './eagle/utils/Auth.php';
require_once './eagle/models/User.php';

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

$app->get('/directory', function($req, $res, $args) {
	Auth::redirectIfNotLoggedIn();
	$this->view->render($res, 'directory.html', [
		'title' => 'User Directory',
		'loggedIn' => Auth::checkLoggedIn(),
		'user' => Auth::getLoggedInUser(),
		'users' => User::all()
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

$app->get('/register', function($req, $res, $args) {
/*	Auth::redirectIfNotLoggedIn();
*/	$this->view->render($res, 'register.html', [
		'title' => 'Register User',
		'loggedIn' => Auth::checkLoggedIn(),
		'user' => Auth::getLoggedInUser()
	]);
});

$app->post('/register', function($req, $res, $args) {
	$user = new User();
	$user->name = $_POST['name'];
	$user->email = $_POST['email'];
	$user->password = Auth::hash($_POST['password']);
	$user->rank = 5;
	$user->save();
	return $res->withStatus(302)->withHeader('Location', '/');
});