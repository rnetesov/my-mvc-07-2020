<?php

$app->get('admin.panel', '/admin/panel', \Actions\Admin\CabinetAction::class, [
	'add' => $container->get(\Middlewares\BasicAuthMiddleare::class)
]);
$app->get('test.route', '/test', \Controllers\TestController::class.':index');

$app->get('city.all', '/world/city/all',$container->get(\Actions\World\City\AllAction::class), [
	'defaults' => ['limit' => null],
	'tokens' => ['limit' => '\d+'],
]);
$app->get('city.one', '/world/city/{id}', $container->get(\Actions\World\City\OneAction::class), [
	'tokens' => ['id' => '\d+']
]);
$app->get('city.code', '/world/city/{countryCode}', $container->get(\Actions\World\City\CodeAction::class) ,[
	'tokens' => ['countryCode' => '[a-zA-Z]{3}']
]);
$app->get('city.find.name', '/world/city/find/{name}', $container->get(\Actions\World\City\FindNameAction::class), [
	'tokens' => ['name' => '[a-zA-Z]+']
]);

