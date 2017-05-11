<?php 

	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	$app = new \Slim\App;

	$app->get('/', function () {
		echo "OK";
	});

	$app->run();

 ?>