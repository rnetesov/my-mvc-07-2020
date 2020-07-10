<?php

namespace Actions\Test;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class Test
{
	public function __invoke(ServerRequestInterface $request)
	{
		return new HtmlResponse('test');
	}
}