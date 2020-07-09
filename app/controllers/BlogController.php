<?php


namespace controllers;


use base\Controller;

class BlogController extends Controller
{
	public function actionIndex(array $args) {
		$name = $args['name'];
		$id = $args['id'] ?? null;

		echo $name .' '.$id;

	}
}