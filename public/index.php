<?php

/* @var \Psr\Http\Message\ResponseInterface $response */

use Aura\Router\RouterContainer;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Sys\Dispatcher;

require_once __DIR__.'/../vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals(
	$_SERVER,
	$_GET,
	$_POST,
	$_COOKIE,
	$_FILES
);

$response = new Response();

$routerContainter = new RouterContainer();

$map = $routerContainter->getMap();

$map->get('test', '/test', new \Actions\Test\Test());
$map->get('blog.get', '/blog/get', Actions\Blog\GetAction::class);
$map->get('blog.add', '/blog/add', Actions\Blog\AddAction::class);
$map->get('blog.delete', '/blog/delete', Actions\Blog\DeleteAction::class);
$map->get('cart.add', '/cart/add', Actions\Cart\AddAction::class);
$map->get('cart.delete', '/cart/delete', Actions\Cart\DeleteAction::class);
$map->get('controller.test', '/test/index', \Controllers\TestController::class.':index');


$matcher = $routerContainter->getMatcher();

$route = $matcher->match($request);

if ($route) {
	foreach ($route->attributes as $key => $value) {
		$request = $request->withAttribute($key, $value);
	}
	$handler = $route->handler;
	$dispatcher = new Dispatcher($handler);
	$callable = $dispatcher->dispatch();
	$response = $callable($request);
}

if (!$route) {
	$failedRoute = $matcher->getFailedRoute();
	switch ($failedRoute->failedRule) {
		case 'Aura\Router\Rule\Allows':
			$response =  $response->withStatus(405);
			$textResponse = $response->getReasonPhrase().'<br>ALLOWED METHODS: '.implode(',', $failedRoute->allows);
			$response->getBody()->write($textResponse);
			break;
		case 'Aura\Router\Rule\Accepts':
			$response = $response =  $response->withStatus(406);
			$response = $response->getBody()->write($response->getReasonPhrase());
			break;
		default:
			$response = $response->withStatus(404);
			$response->getBody()->write($response->getReasonPhrase());
			break;
	}
}

$response = $response
	->withHeader('X-some-header', 'testing')
	->withHeader('X-developer-name', 'roman')
	->withHeader('X-php-ver', phpversion());

$emitter = new SapiEmitter();
$emitter->emit($response);



