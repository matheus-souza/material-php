<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Product;
use \Slim\Http\Request;

$app->get("/admin/products", function () {
    User::verifyLogin();

    $search = $_GET['search'] ?? "";
    $page = $_GET['page'] ?? 1;

    $pagination = Product::getPage($page, 10, $search ?? null);

    $pages = [];

    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/admin/products?'.http_build_query([
                    'page' => $x+1,
                    'search' => $search
                ]),
            'text'=>$x+1
        ]);
    }

    $page = new PageAdmin();

    $page->setTpl("products", array(
        "products"  => $pagination['data'],
        "search" => $search,
        "pages" => $pages
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

$app->get('/admin/products/{idproduct}/delete', function (Request $request) {
    User::verifyLogin();

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $product->delete();

    header("Location: /admin/products");
    exit();
});

?>