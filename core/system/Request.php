<?php


namespace system;


class Request
{
	public function get($name)
	{
		return $_GET[$name] ?? null;
	}

	public function post($name)
	{
		return $_POST[$name] ?? null;
	}

	public function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getHeaders()
	{
		return apache_request_headers();
	}

	public function isGet()
	{
		return ($this->getMethod()=='GET') ? true : false;
	}

	public function isPost()
	{
		return ($this->getMethod()=='POST') ? true : false;
	}

	public function isPut()
	{
		return ($this->getMethod()=='PUT') ? true : false;
	}

	public function isDelete()
	{
		return ($this->getMethod()=='DELETE') ? true : false;
	}


}