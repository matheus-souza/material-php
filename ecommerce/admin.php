<?php

use \Page\PageAdmin;
use \Models\User;

$app->get('/admin', function () {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("index");
});

$app->get('/admin/login', function () {
    $page = new PageAdmin(array("header" => false, "footer" => false));

    $page->setTpl("login");
});

$app->post('/admin/login', function () {
    User::login($_POST['login'], $_POST['password']);

    header('Location: /admin');
    exit;
});

$app->get('/admin/logout', function () {
    User::logout();

    header("Location: /admin/login");
    exit;
});

?>