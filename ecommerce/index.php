<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Db\Sql;
	use \Page\Page;
	use \Page\PageAdmin;
	use \Models\User;

	$app = new App;

	$app->get('/', function () {
		
		$page = new Page();

		$page->setTpl("index");
	});

	$app->get('/admin', function () {

	    User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("index");
	});

	$app->get('/admin/login', function () {
	   $page = new PageAdmin(array("header" => false, "footer" => false));

	   $page->setTpl("login");
    });

	$app->post('/admin/login', function () {
	    User::login($_POST['login'], $_POST['password']);

	    header('Location: /admin');
	    exit;
    });
	$app->run();

 ?>