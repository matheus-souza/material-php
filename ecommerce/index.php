<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Page\Page;
    use \Models\Product;

	$app = new App;

	require_once "site-categories.php";
    require_once "site-producst.php";
    require_once "admin.php";
    require_once "admin-users.php";
    require_once "admin-login.php";
    require_once "admin-categories.php";
    require_once "admin-products.php";
    require_once "functions.php";

    $app->get('/', function () {

        $products = Product::listAll();

        $page = new Page();

        $page->setTpl("index", array(
            'products' => Product::checkList($products)
        ));
    });

    $app->get('/cart', function () {
        $page = new Page();

        $page->setTpl('cart');
    });

	$app->run();

 ?>