<?php

namespace Controllers;


use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BlogController
{
	public function add(ServerRequestInterface $request, RequestHandlerInterface $handler)
	{
		return new HtmlResponse(__METHOD__);
	}

	public function get(ServerRequestInterface $request, RequestHandlerInterface $handler)
	{
		$id = $request->getAttribute('id');
		return new HtmlResponse('get post: '. $id);
	}

}