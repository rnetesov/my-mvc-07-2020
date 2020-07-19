<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProfilerMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$start = microtime(1);
		$response = $handler->handle($request);
		$stop = microtime(1);
		$diffTime = $stop - $start;
		return $response->withHeader('Script-run-time', $diffTime);
	}
}