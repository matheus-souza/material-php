<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Order;
use \Models\Product;
use \Slim\Http\Request;

$app->get('/admin/orders', function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl('orders', [
        'orders' => Order::listAll()
    ]);
});


?>