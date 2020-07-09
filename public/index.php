<?php

use system\Router;


require_once __DIR__.'/../vendor/autoload.php';

define('VIEW_PATH', __DIR__.'/../app/views/');
define('TEMPLATE_PATH', __DIR__.'/../app/templates/');
define('VIEW_EXT', 'php');

try {
	$router = Router::getInstance();

	$router->add(new \system\Route('/', 'welcome', 'index'));
	$router->add(new \system\Route('/test(/<id>)', 'test', 'index', ['id' => '\d+']));
	$router->add(new \system\Route('news(/<year>(/<month>(/<day>)))', 'news', 'index', [
		'year' => '20\d{2}|19\d{2}',
		'month' => '\d{2}',
		'day' => '\d{2}'
	]));

	$response = $router->dispatch();
	echo $response;
} catch (\system\exceptions\RouterException $e) {
	http_response_code($e->getCode());
	echo $e->getMessage();
}