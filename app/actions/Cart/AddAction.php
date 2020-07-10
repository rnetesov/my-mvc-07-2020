<?php

namespace Actions\Cart;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class AddAction
{
	public function __invoke(ServerRequestInterface $request)
	{
		return new HtmlResponse('Add Cart Action');
	}
}