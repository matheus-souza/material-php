<?php

use Models\Product;
use Page\Page;
use Slim\Http\Request;

$app->get('/products/{desurl}', function (Request $request) {
    $product = new Product();

    $product->getFromUrl($request->getAttribute('desurl'));

    $page = new Page();

    $page->setTpl('product-detail', [
        'product' => $product->getValues(),
        'categories' => $product->getCategories()
    ]);
});

?>