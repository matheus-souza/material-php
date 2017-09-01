<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Product;

$app->get("/admin/products", function () {
    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", array(
        "products"  => $products
    ));
});


?>