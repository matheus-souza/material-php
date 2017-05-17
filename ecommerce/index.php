<?php 

	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Db\Sql;

	$app = new App;

	$app->get('/', function () {
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users");

		echo json_encode($results);
	});

	$app->run();

 ?>