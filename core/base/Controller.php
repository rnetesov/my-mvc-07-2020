<?php

namespace base;


use system\Request;

abstract class Controller
{
	protected $request;

	public function __construct()
	{
		$this->request = new Request();
	}
}