<?php

require_once './eagle/utils/Downloader.php';
require_once './eagle/utils/FileReader.php';
require_once './eagle/utils/Auth.php';

$app->group('/user', function() {

	$this->get('/{url:.*\/}', function($req, $res, $args) {
		header('Location:/user/' . trim($args['url'], '/'));
		exit();
	});

	$this->get('/{id}/delete', function($req, $res, $args) {
		User::delete($args['id']);
		header('Location:/directory');
		exit();
	});

});