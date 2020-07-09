<?php


namespace base;


class View
{
	private static $loader = null;
	private static $twig = null;
	protected $data = [];
	protected $controller_name;

	public function __construct()
	{
		if (is_null(self::$loader)) {
			self::$loader = new \Twig\Loader\FilesystemLoader(TEMPLATE_PATH);
			$cache = (TEMPLATE_CACHE) ? ['cache' => CACHE_TEMPLATE_PATH] : [];
			self::$twig = new \Twig\Environment(self::$loader, $cache);

			if (RELOAD_TEMPLATE) self::$twig->enableAutoReload();
		}
	}

	public function render($view, $data = [])
	{
		$res = array_merge($this->data, $data);
		return self::$twig->render($view.'.'.VIEW_EXT, $res);
	}

	public function set($name, $value)
	{
		$this->data[$name] = $value;
		return $this;
	}

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		return ($this->data[$name]) ?? null;
	}
}