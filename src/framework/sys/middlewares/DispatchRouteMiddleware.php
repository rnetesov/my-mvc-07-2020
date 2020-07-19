<?php


namespace Sys\Middlewares;


use Aura\Router\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Router\RouteResolver;

class DispatchMiddleware implements MiddlewareInterface
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/** @var  $route Route */
		$route = $request->getAttribute(Route::class);

		if ($route) {
			$routeResolver = new RouteResolver($this->container);
			$middleware = $routeResolver->resolve($route->handler);
			return $middleware->process($request, $handler);
		}
		return $handler->handle($request);
	}
}