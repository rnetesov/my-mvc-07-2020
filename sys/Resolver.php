<?php

namespace Sys;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Laminas\Stratigility\middleware;

class Resolver
{
	protected $handler;

	public function resolve($handle)
	{
		if (is_string($handle) && strstr($handle, ':'))
		{
			$delimetrPos = stripos($handle, ':');
			$class = substr($handle, 0, $delimetrPos);
			$action = substr($handle, $delimetrPos+1);
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle, $class, $action) {
				$cobj = null;
				if (class_exists($class)) $cobj = new $class();
				if (is_object($cobj) && method_exists($cobj, $action)) return $cobj->$action($request, $handler);
				if (is_null($cobj)) return new HtmlResponse('Class '.$class.' not found', 404);
				return new HtmlResponse('Action '.$action.' not found', 404);
			});
		}

		if (is_string($handle)) {
			if (!class_exists($handle)) return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				return new HtmlResponse('Class '.$handle.' not found', 404);
			});
			if (is_subclass_of(new $handle, MiddlewareInterface::class)) return new $handle;
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				$reflection = new \ReflectionClass($handle);
				if ($reflection->hasMethod('__invoke')) return (new $handle)($request, $handler);
				return new HtmlResponse('Method __invoke for '.$handle.' not found', 404);
			});
		}

		if (is_object($handle)) {
			if (is_subclass_of($handle, MiddlewareInterface::class)) {
				return $handle;
			}
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				return $handle($request, $handler);
			});
		}

		if (is_callable($handle)) {
			return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
				return $handle($request, $handler);
			});
		}

		if (is_array($handle)) {
			$pipeline = $this->createPipe($handle);
			return  $pipeline;
		}

		return middleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($handle) {
			return new HtmlResponse('Incorrect type handle for route '. print_r($handle, 1));
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