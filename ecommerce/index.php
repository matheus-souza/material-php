<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Slim\Http\Request;
	use \Db\Sql;
	use \Page\Page;
	use \Page\PageAdmin;
	use \Models\User;
	use \Models\Category;

	$app = new App;

	require_once "site-categories.php";
	require_once "admin.php";
	require_once "admin-users.php";
	require_once "admin-login.php";
	require_once "admin-categories.php";


    $app->get('/', function () {

        $page = new Page();

        $page->setTpl("index");
    });

	$app->run();

 ?>