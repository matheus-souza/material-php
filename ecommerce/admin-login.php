<?php


$app->get('/admin/forgot', function () {
    $page = new PageAdmin(array("header" => false, "footer" => false));

    $page->setTpl("forgot");
});

$app->post('/admin/forgot', function () {
    $user = User::getForgot($_POST["email"]);

    header("Location: /admin/forgot/sent");
    exit();
});

$app->get('/admin/forgot/sent', function () {
    $page = new PageAdmin(array("header" => false, "footer" => false));

    $page->setTpl("forgot-sent");
});

$app->get('/admin/forgot/reset', function () {
    $user = User::validForgotDecryp($_GET["code"]);

    $page = new PageAdmin(array("header" => false, "footer" => false));

    $page->setTpl("forgot-reset", array(
        "name" => $user["desperson"],
        "code" => $_GET["code"]
    ));
});

$app->post('/admin/forgot/reset', function () {
    $forgot = User::validForgotDecryp($_POST["code"]);

    User::setForgotUsed($forgot["idrecovery"]);

    $user = new User();

    $user->get((int)$forgot["iduser"]);

    $user->setPassword($_POST["password"]);

    $page = new PageAdmin(array("header" => false, "footer" => false));

    $page->setTpl("forgot-reset-success");
});

?>