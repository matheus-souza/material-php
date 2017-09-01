<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Page\Page;

	$app = new App;

	require_once "site-categories.php";
	require_once "admin.php";
	require_once "admin-users.php";
	require_once "admin-login.php";
	require_once "admin-categories.php";
	require_once "admin-products.php";

    $app->get('/', function () {

        $page = new Page();

        $page->setTpl("index");
    });

	$app->run();

 ?>