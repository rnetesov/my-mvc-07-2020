<?php

use Aura\Router\RouterContainer;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipe;

require_once __DIR__.'/../vendor/autoload.php';

$routerContainter = new RouterContainer();

$map = $routerContainter->getMap();

$map->get('default', '/', \Actions\Test\Test::class);
$map->get('blog.get', '/blog/{id}', \Controllers\BlogController::class.':get')->tokens(['id' => '\d+']);
$map->get('blog.add', '/blog', \Controllers\BlogController::class.':add');

$pipeline = new MiddlewarePipe();

$pipeline->pipe(new \Middlewares\ProfilerMiddleware());
$pipeline->pipe(\Laminas\Stratigility\path('/blog', new \Middlewares\BasicAuthMiddleware()));
$pipeline->pipe(new \Sys\Middlewares\TestMiddleware());
$pipeline->pipe(new \Sys\Middlewares\RouteMiddleware($routerContainter, $pipeline));
$pipeline->pipe(new \Sys\Middlewares\RouteNotFoundMiddleware());

$server = new RequestHandlerRunner(
	$pipeline,
	new SapiEmitter(),
	static function() {
		return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
	},
	static function(Throwable $e) {
		$response = (new ResponseFactory())
			->createResponse(500)
			->getBody()->write(sprintf(
				'An error occurred: %s',
				$e->getMessage()));
	}
);

$server->run();



