<?php

use \Page\PageAdmin;
use \Models\User;
use \Models\Order;
use \Models\OrderStatus;
use \Models\Product;
use \Slim\Http\Request;

$app->get('/admin/orders/{idorder}/delete', function (Request $request) {
    User::verifyLogin();

    $order = new Order();

    $order->get((int)$request->getAttribute('idorder'));

    $order->delete();

    header("Location: /admin/orders");
    exit();
});

$app->post('/admin/orders/{idorder}/status', function (Request $request) {
    User::verifyLogin();

    $idOrder = (int)$request->getAttribute('idorder');

    if (!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0) {
        Order::setError("Informe o status atual.");

        header("Location: /admin/orders/".$idOrder."/status");
        exit();
    }

    $order = new Order();

    $order->get($idOrder);

    $order->setidstatus((int)$_POST['idstatus']);

    $order->save();

    Order::setSuccess("Status atualizado");

    header("Location: /admin/orders/{$idOrder}/status");
    exit();
});

$app->get('/admin/orders/{idorder}/status', function (Request $request) {
    User::verifyLogin();

    $order = new Order();

    $order->get((int)$request->getAttribute('idorder'));

    $page = new PageAdmin();

    $page->setTpl("order-status", [
        'order' => $order->getValues(),
        'status' => OrderStatus::listAll(),
        'msgSuccess' => Order::getSuccess(),
        'msgError' => Order::getError(),
    ]);
});

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