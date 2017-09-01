<?php


$app->get("/admin/products", function () {
    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", array(
        "products"  => $products
    ));
});


?>