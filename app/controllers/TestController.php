<?php


namespace controllers;


class TestController
{
	public function actionIndex(array $args)
	{
		$id = $args['id'] ?? null;
		return "Hello I'm Controller:test, action: index ".$id;
	}
}