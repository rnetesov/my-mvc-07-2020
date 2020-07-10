<?php

namespace Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class TestController
{
	public function index(ServerRequestInterface $request) {
		return new HtmlResponse('Hello index from test controller');
	}
}