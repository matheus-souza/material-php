<?php

use Models\Category;
use Models\Product;
use Models\User;
use Page\PageAdmin;
use Slim\Http\Request;

$app->get('/admin/categories', function () {
    User::verifyLogin();

    $search = $_GET['search'] ?? "";
    $page = $_GET['page'] ?? 1;

    $pagination = Category::getPage($page, 10, $search ?? null);

    $pages = [];

    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/admin/categories?' . http_build_query([
                    'page' => $x + 1,
                    'search' => $search
                ]),
            'text' => $x + 1
        ]);
    }


    $page = new PageAdmin();

    $page->setTpl("categories", array(
        "categories" => $pagination['data'],
        "search" => $search,
        "pages" => $pages
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
        'category' => $category->getValues()
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
        'category' => $category->getValues(),
        'productsRelated' => $category->getProducts(),
        'productsNotRelated' => $category->getProducts(false)
    ]);
});

$app->get('/admin/categories/{idcategory}/products/{idproduct}/add', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $category->addProduct($product);

    header("Location: /admin/categories/" . (int)$request->getAttribute('idcategory') . "/products");
    exit();
});

$app->get('/admin/categories/{idcategory}/products/{idproduct}/remove', function (Request $request) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $product = new Product();

    $product->get((int)$request->getAttribute('idproduct'));

    $category->removeProduct($product);

    header("Location: /admin/categories/" . (int)$request->getAttribute('idcategory') . "/products");
    exit();
});

?>