<?php

use system\Router;

require_once __DIR__.'/../core/psr4autoloader.php';

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('system', __DIR__.'/../core/system');
$loader->addNamespace('base', __DIR__.'/../core/base');
$loader->addNamespace('models', __DIR__.'/../app/models');
$loader->addNamespace('controllers', __DIR__.'/../app/controllers');

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