<?php


namespace controllers;


use base\Controller;

class WelcomeController extends Controller
{
	public function actionIndex() {
		return 'Welcome';
	}
}