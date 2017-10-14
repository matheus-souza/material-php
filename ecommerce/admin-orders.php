<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Order;
use \Models\OrderStatus;
use \Models\Product;
use \Slim\Http\Request;


$app->get('/admin/orders/{idorder}', function (Request $request) {
    User::verifyLogin();

    $order = new Order();

    $order->get((int)$request->getAttribute('idorder'));

    $cart = $order->getCart();

    $page = new PageAdmin();

    $page->setTpl("order", [
        'order' => $order->getValues(),
        'cart' => $cart->getValues(),
        'products' => $cart->getProducts()
    ]);
});

$app->get('/admin/orders', function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl('orders', [
        'orders' => Order::listAll()
    ]);
});


?>