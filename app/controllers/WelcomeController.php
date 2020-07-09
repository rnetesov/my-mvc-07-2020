<?php


namespace controllers;


use base\Controller;
use base\View;

class WelcomeController extends Controller
{
	public function actionIndex() {
		$name       = 'john';
		$surname    = 'Millosovish';
		$age        = 29;

		$item = [
			'id' => 2,
			'title' => 'Some product',
			'price' => 127.45,
			'cnt' => 4,
			'with-status' => 23
		];

		$users = [
			['name' => 'john', 'password' => md5('1234')],
			['name' => 'nick', 'password' => md5('qwerty')],
			['name' => 'martin', 'password' => md5('toorbal123')]
		];

		$this->view
			->set("name", $name)
			->set("surname", $surname)
			->set("age", $age)
			->set("item", $item)
			->set("users", $users);

		return $this->view->render('welcome/index');
	}

	public function actionHello() {

	}
}