<?php 

	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Db\Sql;
	use \Page\Page;
	use \Page\PageAdmin;

	$app = new App;

	$app->get('/', function () {
		
		$page = new Page();

		$page->setTpl("index");
	});

	$app->get('/admin', function () {
		
		$page = new PageAdmin();

		$page->setTpl("index");
	});

	$app->get('/admin/login', function () {
	   $page = new PageAdmin(array("header" => false, "footer" => false));

	   $page->setTpl("login");
    });


	$app->run();

 ?>