<?php


namespace Sys\Middlewares;


use Aura\Router\RouterContainer;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Resolver;

class RouteMiddleware implements MiddlewareInterface
{
	protected $routeContainter;
	protected $pipeline;

	public function __construct(RouterContainer $routerContainer, MiddlewarePipe $pipeline)
	{
		$this->routeContainter = $routerContainer;
		$this->pipeline = $pipeline;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$matcher = $this->routeContainter->getMatcher();
		$route = $matcher->match($request);

		if (!$route) {
			$failedRule = $matcher->getFailedRoute();
			$request = $request->withAttribute('failedRule', $failedRule->failedRule);
			return $handler->handle($request);
		}

		foreach ($route->attributes as $key => $value) {
			$request = $request->withAttribute($key, $value);
		}

		$resolver = new Resolver();
		$middleware = $resolver->resolve($route->handler);
		return $middleware->process($request, $handler);
	}
}