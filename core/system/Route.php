<?php


namespace system;


class Route
{
	private $rule;
	private $controller;
	private $action;
	private $tokens;
	private static $default_regexp = '[a-zA-Z0-9-_]+';
	private $params = [];

	public function __construct($rule, $controller, $action, $tokens = [])
	{
		$this->rule = trim($rule, '/');
		$this->controller = $controller;
		$this->action = $action;
		$this->tokens = $tokens;
	}

	public function match()
	{
		$url = $this->getPath();
		$pattern = '#^'.$this->buildRegExp().'$#';

		if (preg_match($pattern, $url, $matches)) {
			foreach ($matches as $key => $value) {
				if (is_int($key)) continue;
				$this->params[$key] = $value;
			}
			return true;
		}
		return false;
	}

	public function getPath()
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url)[0];
		$url = trim($url, '/');
		return $url;
	}

	public function getControllerName()
	{
		return $this->controller;
	}

	public function getActionName()
	{
		return $this->action;
	}

	public function getParams()
	{
		return $this->params;
	}

	private function buildRegExp()
	{
		$regexp = str_replace(')', ')?', $this->rule);
		$regexp = str_replace('<', '(?P<', $regexp);
		$regexp = str_replace('>', '>'.self::$default_regexp.')', $regexp);
		$regexp = str_replace('/', '\/', $regexp);
		if (!empty($this->tokens)) {
			foreach ($this->tokens as $name => $reg) {
				$regexp = str_replace($name.'>'.self::$default_regexp, $name.'>'.$reg, $regexp);
			}
		}
		return $regexp;
	}
}