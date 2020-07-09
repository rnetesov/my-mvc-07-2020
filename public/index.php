<?php

use system\Router;


require_once __DIR__.'/../vendor/autoload.php';

define('ROOTE_PATH', dirname(__DIR__).'/');
define('APP_PATH', ROOTE_PATH.'app/');
define('TEMPLATE_PATH', APP_PATH.'views/');
define('CACHE_PATH', ROOTE_PATH.'cache/');
define('CACHE_TEMPLATE_PATH', CACHE_PATH.'views');
define('VIEW_EXT', 'twig');
define('TEMPLATE_CACHE', true);
define('RELOAD_TEMPLATE', true);

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