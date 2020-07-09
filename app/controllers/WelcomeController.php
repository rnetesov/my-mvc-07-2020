<?php


namespace controllers;


use base\Controller;
use base\View;

class WelcomeController extends Controller
{
	public function actionIndex() {
		$view = new View('welcome/index');
		$view->name = 'John';
		$view->age = 29;
		$html = $view->renderTemplate('layout');
		return $html;
	}
}