<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/models/User.php';
require_once './eagle/utils/Auth.php';

$app->group('/user', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/user/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{id}/delete', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$user = Auth::getLoggedInUser();
		if ($user->rank >= 9)
		{
			User::delete($args['id']);
			header('Location:/directory');
			exit();
		}
		else
		{
			header('Location:/directory');
			exit();
		}
	});

	$this->get('/{id}/update', function($req, $res, $args) {
		Auth::redirectIfNotLoggedIn();
		$user = User::where('id', (int)$args['id'])->first();
		$this->view->render($res, 'update.html', [
			'title' => 'Update User',
			'loggedIn' => Auth::checkLoggedIn(),
			'user' => Auth::getLoggedInUser(),
			'userToEdit' => $user
		]);
	});

	$this->post('/{id}/update', function($req, $res, $args) {
		$user = User::where('id', (int)$args['id'])->first();
		/*var_dump($user);
		die();*/
		if (Auth::getLoggedInUser()->rank > 9)
		{
			if (isset($_POST['name']))
			{
				$user->name = $_POST['name'];
			}
			if (isset($_POST['email']))
			{
				$user->email = $_POST['email'];
			}
			if (isset($_POST['rank']))
			{
				$user->rank = $_POST['rank'];
			}
			if (isset($_POST['password']))
			{
				$user->password = $_POST['password'];
			}
			$user->save();
		}
		header('Location:/directory');
		exit();
	});

});