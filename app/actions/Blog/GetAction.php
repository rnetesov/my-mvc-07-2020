<?php

namespace Actions\Blog;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class GetAction
{
	public function __invoke(ServerRequestInterface $request)
	{
		return new HtmlResponse('get action');
	}
}