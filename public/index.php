<?php

use system\Router;

chdir(dirname(__DIR__));

require_once 'core/psr4autoloader.php';

define('VIEW_PATH', 'app/views/');
define('TEMPLATE_PATH', 'app/templates/');
define('VIEW_EXT', 'php');


$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('system', 'core/system');
$loader->addNamespace('base', 'core/base');
$loader->addNamespace('models', 'app/models');
$loader->addNamespace('controllers', 'app/controllers');


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