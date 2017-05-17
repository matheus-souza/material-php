<?php 

	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Db\Sql;
	use \Page\Page;

	$app = new App;

	$app->get('/', function () {
		
		$page = new Page();

		$page->setTpl("index");
	});

	$app->run();

 ?>