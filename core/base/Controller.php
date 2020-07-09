<?php

namespace base;


use system\Request;

abstract class Controller
{
	protected $request;
	protected $view;

	public function __construct()
	{
		$this->request = new Request();
		$this->view = new View();
	}
}