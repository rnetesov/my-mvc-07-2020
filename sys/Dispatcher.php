<?php

namespace Sys;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher
{
	private $handler;

	public function __construct($handler)
	{
		$this->handler = $handler;
	}

	public function dispatch() : callable {
		$handler = $this->handler;

		if (is_string($handler) && strstr($handler, ':')) {
			$object = null;
			$delimetrPos = stripos($handler, ':');
			$class = substr($handler, 0, $delimetrPos);
			$action = substr($handler, $delimetrPos+1);

			if (class_exists($class)) $object = new $class;

			if (is_object($object) && method_exists($object, $action))
				return function(ServerRequestInterface $request) use ($object, $action) : Response {
					return $object->$action($request);
				};
			if (!is_object($object)) {
				return function(ServerRequestInterface $request) use ($class) {
					return new Response\HtmlResponse('Controller '. $class .' not found', 404);
				};
			}
			if (!method_exists($object, $class)) {
				return function (ServerRequestInterface $request) use ($action) {
					return new Response\HtmlResponse('Action ' . $action . ' not found', 404);
				};
			}
		}

		if (is_string($handler)) {
			return function (ServerRequestInterface $request) use ($handler) {
				return (new $handler)($request);
			};
		}

		if (is_callable($handler) || is_object($handler)) return $handler;

		return function (ServerRequestInterface $request) {
			return (new Response('Handler for route not correct'))
				->withStatus('404');
		};
	}
}