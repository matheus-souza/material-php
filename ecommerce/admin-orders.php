<?php

use Models\Order;
use Models\OrderStatus;
use Models\User;
use Page\PageAdmin;
use Slim\Http\Request;

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

        header("Location: /admin/orders/" . $idOrder . "/status");
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

    $search = $_GET['search'] ?? "";
    $page = $_GET['page'] ?? 1;

    $pagination = Order::getPage($page, 10, $search ?? null);

    $pages = [];

    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/admin/orders?' . http_build_query([
                    'page' => $x + 1,
                    'search' => $search
                ]),
            'text' => $x + 1
        ]);
    }

    $page = new PageAdmin();

    $page->setTpl('orders', [
        'orders' => $pagination['data'],
        "search" => $search,
        "pages" => $pages
    ]);
});


?>