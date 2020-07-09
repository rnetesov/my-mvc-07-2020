<?php

namespace base;

class View
{
	private $view_path;
	private $template_path;
	private $data = [];

	public function __construct($view)
	{
		$this->view_path = VIEW_PATH.$view.'.'.VIEW_EXT;
	}

	public function render($data = [])
	{
		$data = array_merge($this->data, $data);
		ob_start();
		extract($data);
		if (file_exists($this->view_path)) require_once $this->view_path;
		else echo 'File view <b><i>'.$this->view_path.'</i></b> not found';
		$result = ob_get_clean();
		return $result;
	}

	public function renderTemplate($name_template, $data = [])
	{
		$this->template_path = TEMPLATE_PATH.$name_template.'.'.VIEW_EXT;
		ob_start();
		$data = array_merge($this->data, $data);
		extract($data);
		$content = ($this->view_path) ? $this->render() : null;
		if (file_exists($this->template_path)) require_once $this->template_path;
		else echo 'File template <b><i>'.$this->template_path.'</i></b> not found';
		$result = ob_get_clean();
		return $result;
	}

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		if (isset($this->data[$name]))
			return $this->data[$name];
		return null;
	}
}