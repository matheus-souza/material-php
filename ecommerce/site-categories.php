<?php

use \Slim\Http\Request;
use \Page\Page;
use \Models\Category;
use \Models\Product;

$app->get('/categories/{idcategory}', function (Request $request) {
    $category = new Category();

    $category->get((int)$request->getAttribute('idcategory'));

    $page = new Page();

    $page->setTpl("category", array(
        "category"=>$category->getValues(),
        "products"=>Product::checkList($category->getProducts())
    ));

});



?>