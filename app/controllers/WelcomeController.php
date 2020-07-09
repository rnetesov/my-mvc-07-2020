<?php


namespace controllers;


use base\Controller;
use base\View;

class WelcomeController extends Controller
{
	public function actionIndex() {

		if ($this->request->isGet()) {
			$view = new View('welcome/index');
			$view->name = 'John';
			$view->age = 29;
			$view->headers = $this->request->getHeaders();

			$html = $view->renderTemplate('layout');
			return $html;
		}

		if ($this->request->isPost()) {

			echo $this->request->getMethod();
		}

		if ($this->request->isPut()) {
			return $this->request->getMethod();
		}
	}
}