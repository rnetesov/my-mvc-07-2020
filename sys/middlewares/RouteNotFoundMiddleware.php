<?php


namespace Sys\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteNotFoundMiddleware implements MiddlewareInterface
{

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$failedRule = $request->getAttribute('failedRule');
		switch ($failedRule) {
			case 'Aura\Router\Rule\Allows':
				$codeResponse = 405;
				break;
			case 'Aura\Router\Rule\Accepts':
				$codeResponse = 406;
				break;
			default:
				$codeResponse = 404;
				break;
		}
		$response = (new \Laminas\Diactoros\Response())
			->withStatus($codeResponse);
		$response->getBody()->write(strtoupper($response->getReasonPhrase(). ' '.$response->getStatusCode()));
		return $response;
	}
}