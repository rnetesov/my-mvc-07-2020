<?php


namespace Middlewares;


use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BasicAuthMiddleware implements MiddlewareInterface
{
	protected $users = [
		'john' => '1234',
		'admin' => 'admin',
		'root' => '4321'
	];

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
		$password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

		if ($username && $password)
		{
			foreach ($this->users as $name => $pass) {
				if ($name == $username && $pass == $password)
					return $handler->handle($request);
			}
		}
		return (new Response())
			->withHeader('WWW-Authenticate', 'Basic realm="My Realm"')
			->withStatus(401);
	}
}