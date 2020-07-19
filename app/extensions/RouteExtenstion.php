<?php

namespace Extensions;

use Aura\Router\RouterContainer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtenstion extends AbstractExtension
{
	private $generator;

	public function __construct(RouterContainer $container)
	{
		$this->generator = $container->getGenerator();
	}

	public function getFunctions()
	{
		return [
			new TwigFunction('path', [$this, 'generatePath'])
		];
	}

	public function generatePath($name, array $params = [])
	{
		return $this->generator->generate($name, $params);
	}
}