<?php

use \Slim\Http\Request;
use \Page\PageAdmin;
use \Models\User;
use \Models\Category;

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

?>