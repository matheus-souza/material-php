<?php 

	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	$app = new \Slim\App;

	$app->get('/', function () {
		$sql = new \Custom\Sql();

		$results = $sql->select("SELECT * FROM tb_users");

		echo json_encode($results);
	});

	$app->run();

 ?>