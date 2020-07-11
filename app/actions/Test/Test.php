<?php

namespace Actions\Test;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Test
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
	{
		return new HtmlResponse('test');
	}
}