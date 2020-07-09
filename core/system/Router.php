<?php

namespace system;

use system\exceptions\RouterException;

class Router
{
	private $routes = [];
	private static $instance = null;

	public static function getInstance()
	{
		if (is_null(self::$instance)) return  self::$instance = new self();
		return self::$instance;
	}

	public function add(Route $route)
	{
		array_push($this->routes, $route);
	}

	public function dispatch()
	{
		/**
		 * @var $route Route
		 */
		foreach ($this->routes as $route) {
			if ($route->match()) {
				return $this->navigate($route->getControllerName(), $route->getActionName(), $route->getParams());
			}
		}

		throw new RouterException('Router Not Found', 404);
	}

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	private function navigate($controller, $action, $params)
	{
		$controller_name = ucfirst(strtolower($controller)).'Controller';
		$controller_path = '\controllers\\'.$controller_name;

		if (class_exists($controller_path)) {
			$cObj = new $controller_path;
			$action_name = "action".ucfirst($action);

			if (method_exists($cObj, $action_name)) {
				return $cObj->$action_name($params);
			}
			throw new RouterException('Action '. $action_name.' not found', 404);
		}
		throw new RouterException('Controller ' .$controller_path. ' not found', 404);
	}
}