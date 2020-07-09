<?php

namespace controllers;

use base\Controller;

class MainController extends Controller
{
	public function actionIndex()
	{
		return "Hello I'm Controller:main, action: index";
	}
}