<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Product;
use \Slim\Http\Request;

$app->get("/admin/products", function () {
    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", array(
        "products"  => $products
    ));
});

$app->get("/admin/products/create", function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");
});

$app->post("/admin/products/create", function () {
    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();

    header("Location: /admin/products");
    exit();
});


$app->get('/admin/products/{idproduct}', function (Request $request) {
    User::verifyLogin();

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $page = new PageAdmin();

    $page->setTpl("products-update", array(
        "product" => $product->getValues()
    ));
});
$app->post('/admin/products/{idproduct}', function (Request $request) {
    User::verifyLogin();

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $product->setData($_POST);

    $product->save();

    $product->setPhoto($_FILES['file']);

    header("Location: /admin/products");
    exit();
});

?>