<?php


namespace Middlewares;

use Aura\Router\Matcher;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteFailureMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/** @var  $matcher Matcher*/
		$matcher  = $request->getAttribute(Matcher::class);
		$failedRoute = $matcher->getFailedRoute();

		switch ($failedRoute->failedRule) {
			case 'Aura\Router\Rule\Allows':
				$msg = 'METHOD NOT ALLOWED';
				$statusCode = 405;
				break;
			case 'Aura\Router\Rule\Accepts':
				$msg = 'NOT ACCEPTABLE';
				$statusCode = 406;
				break;
			default:
				$msg = 'NOT FOUND';
				$statusCode = 404;
				break;
		}
		return new HtmlResponse($msg, $statusCode);
	}
}