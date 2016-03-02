<?php

session_start();

require_once './vendor/autoload.php';
require_once './eagle/database/Connection.php';

$c = new \Slim\Container(include './eagle/config/config.php');
$app = new \Slim\App($c);

$container = $app->getContainer();

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig('./eagle/templates');
	$view->addExtension(new \Slim\Views\TwigExtension(
		$container['router'],
		$container['request']->getUri()
	));
	return $view;
};

new Connection;

ini_set('max_execution_time', 60);

require_once './eagle/routes/user.routes.php';
require_once './eagle/routes/team.routes.php';
require_once './eagle/routes/event.routes.php';
require_once './eagle/routes/comment.routes.php';
require_once './eagle/routes/scouting.routes.php';
require_once './eagle/routes/base.routes.php';

$app->run();
