<?php

use \Slim\Http\Request;
use \Page\PageAdmin;
use \Models\User;
use \Models\Category;
use \Models\Product;

$app->get('/admin/categories', function () {
    User::verifyLogin();

    $categories = Category::listAll();

    $page = new PageAdmin();

    $page->setTpl("categories", array(
        "categories"=>$categories
    ));
});

$app->get('/admin/categories/create', function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function () {
    User::verifyLogin();

    $category = new Category();

    $category->setData($_POST);

    $category->save();

    header("Location: /admin/categories");
    exit();
});


$app->get('/admin/categories/{idcategory}/delete', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $category->delete();

    header("Location: /admin/categories");
    exit();
});

$app->get('/admin/categories/{idcategory}', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $page = new PageAdmin();

    $page->setTpl("categories-update", array(
        'category'=>$category->getValues()
    ));
});

$app->post('/admin/categories/{idcategory}', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $category->setData($_POST);

    $category->save();

    header("Location: /admin/categories");
    exit();
});

$app->get('/admin/categories/{idcategory}/products', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $page = new PageAdmin();

    $page->setTpl("categories-products", [
        'category'=>$category->getValues(),
        'productsRelated'=>$category->getProducts(),
        'productsNotRelated'=>$category->getProducts(false)
    ]);
});

$app->get('/admin/categories/{idcategory}/products/{idproduct}/add', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $category->addProduct($product);

    header("Location: /admin/categories/".(int)$request->getAttribute('idcategory')."/products");
    exit();
});

?>