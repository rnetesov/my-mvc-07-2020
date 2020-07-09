<?php


namespace controllers;


use base\Controller;

class NewsController extends Controller
{
	public function actionIndex(array $args) {
		$year = $args['year'] ?? null;
		$moth = $args['month'] ?? null;
		$day = $args['day'] ?? null;

		echo "$year/$moth/$day";
	}
}