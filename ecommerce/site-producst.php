<?php

use \Slim\Http\Request;
use \Page\Page;
use \Models\Category;
use \Models\Product;

$app->get('/products/{desurl}', function (Request $request) {
    $product = new Product();

    $product->getFromUrl($request->getAttribute('desurl'));

    $page = new Page();

//    die(var_dump($product->getCategories()));

    $page->setTpl('product-detail', [
        'product' => $product->getValues(),
        'categories'=> $product->getCategories()
    ]);
});

?>