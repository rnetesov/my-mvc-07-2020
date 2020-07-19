<?php

namespace Sys\Services;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Exceptions\ResolverException;
use function Laminas\Stratigility\middleware;

class RouteResolver
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function resolve($handle)
	{
		if (is_string($handle) && strstr($handle, ':'))
		{
			$delimetrPos = stripos($handle, ':');
			$class = substr($handle, 0, $delimetrPos);
			$action = substr($handle, $delimetrPos+1);
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle, $class, $action) {
				$cobj = $this->container->get($class);
				if (method_exists($cobj, $action)) return $cobj->$action($request, $handler);
				throw new  ResolverException('Action '.$action.' not found', 404);
			});
		}

		if (is_string($handle)) {
			$obj = $this->container->get($handle);
			if (is_subclass_of($obj, MiddlewareInterface::class)) return $obj;
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				$reflection = new \ReflectionClass($handle);
				if ($reflection->hasMethod('__invoke')) return ($this->container->get($handle))($request, $handler);
				throw new ResolverException('Method __invoke for '.$handle.' not found', 404);
			});
		}

		if (is_object($handle)) {
			if (is_subclass_of($handle, MiddlewareInterface::class)) {
				return $handle;
			}
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				if (method_exists($handle, '__invoke')) return $handle($request, $handler);
				throw new ResolverException('Method __invoke for '.get_class($handle).' not found', 404);
			});
		}

		if (is_callable($handle)) {
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				return $handle($request, $handler);
			});
		}

		if (is_array($handle)) {
			$pipeline = $this->createPipe($handle);
			return $pipeline;
		}

		return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
			throw new \Exception('Incorrect type handle for route '. print_r($handle, 1));
		});

	}

	public function createPipe(array $handlers)
	{
		$pipiline = new MiddlewarePipe();
		foreach ($handlers as $handle) {
			$middleware = $this->resolve($handle);
			$pipiline->pipe($middleware);
		}
		return $pipiline;
	}
}