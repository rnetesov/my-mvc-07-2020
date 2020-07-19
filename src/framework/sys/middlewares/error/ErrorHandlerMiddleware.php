<?php


namespace Sys\Middlewares;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
	protected $debug;
	protected $twig;

	public function __construct(Environment $twig, bool $debug = false)
	{
		$this->debug = $debug;
		$this->twig = $twig;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try {
			return $handler->handle($request);
		} catch (\Throwable $e) {
			if ($this->debug) {
				return new HtmlResponse($this->twig->render('debug.twig', ['e' => $e]), $this->getStatusCode($e));
			}
			return new HtmlResponse($this->twig->render('prod.twig', ['code' => $this->getStatusCode($e)]), $this->getStatusCode($e));
		}
	}

	public function getStatusCode(\Throwable $e)
	{
		$code = $e->getCode();
		if ($code < 400 || $code > 599) return 500;
		return $code;
	}
}

interface ErrorResponseGenerator
{
	public function generate();
}