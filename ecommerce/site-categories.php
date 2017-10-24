<?php

use Models\Category;
use Page\Page;
use Slim\Http\Request;

$app->get('/categories/{idcategory}', function (Request $request) {
    $page = $_GET['page'] ?? 1;

    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $pagination = $category->getProductsPage($page);

    $pages = [];

    for ($i = 1; $i <= $pagination['pages']; $i++) {
        array_push($pages, [
            'link' => '/categories/' . $category->getidcategory() . '?page=' . $i,
            'page' => $i
        ]);
    }

    $page = new Page();

    $page->setTpl("category", array(
        "category" => $category->getValues(),
        "products" => $pagination['data'],
        "pages" => $pages
    ));

});

?>