<?php

use \Slim\Http\Request;
use \Page\PageAdmin;
use \Models\User;

$app->get('/admin/users/{iduser}/password', function (Request $request) {
    User::verifyLogin();

    $user = new User();

    $user->get((int)$request->getAttribute('iduser'));

    $page = new PageAdmin();

    $page->setTpl('users-password', [
        'user' => $user->getValues(),
        'msgError' => User::getMsgError(),
        'msgSuccess' => User::getMsgSuccess(),
    ]);

});

$app->post('/admin/users/{iduser}/password', function (Request $request) {
    User::verifyLogin();

    $iduser = (int)$request->getAttribute('iduser');

    if (!isset($_POST['despassword']) || $_POST['despassword'] == '') {
        User::setMsgError("Preencha a nova senha");

        header("Location: /admin/users/" . $iduser . "/password");
        exit();
    }

    if (!isset($_POST['despassword-confirm']) || $_POST['despassword-confirm'] == '') {
        User::setMsgError("Preencha a confirmação da nova senha");

        header("Location: /admin/users/" . $iduser . "/password");
        exit();
    }

    if ($_POST['despassword'] != $_POST['despassword-confirm']) {
        User::setMsgError("Confirme corretamete a senha");

        header("Location: /admin/users/" . $iduser . "/password");
        exit();
    }

    $user = new User();

    $user->get($iduser);

    $user->setPassword($_POST['despassword']);

    $_SESSION[User::SESSION] = $user->getValues();

    User::setMsgSuccess("Senha alterada com sucesso");

    header("Location: /admin/users/" . $iduser . "/password");
    exit();
});

$app->get('/admin/users', function () {
    User::verifyLogin();

    $search = $_GET['search'] ?? "";
    $page = $_GET['page'] ?? 1;

    $pagination = User::getPage($page, 10, $search ?? null);

    $pages = [];

    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/admin/users?'.http_build_query([
                    'page' => $x+1,
                    'search' => $search
                ]),
            'text'=>$x+1
        ]);
    }

    $page = new PageAdmin();

    $page->setTpl("users", array(
        "users" => $pagination['data'],
        "search" => $search,
        "pages" => $pages
    ));
});

$app->get('/admin/users/create', function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("users-create");
});

$app->get('/admin/users/{iduser}/delete', function (Request $request) {
    User::verifyLogin();

    $user = new User();

    $user->get((int)$request->getAttribute('iduser'));

    $user->delete();

    header("Location: /admin/users");
    exit();
});

$app->get('/admin/users/{iduser}', function (Request $request) {
    User::verifyLogin();

    $user = new User();

    $user->get((int)$request->getAttribute('iduser'));

    $page = new PageAdmin();

    $page->setTpl("users-update", array(
        "user" => $user->getValues()
    ));
});

$app->post('/admin/users/create', function () {
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->setData($_POST);

    $user->save();

    header("Location: /admin/users");

    exit();
});

$app->post('/admin/users/{iduser}', function (Request $request) {
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->get((int)$request->getAttribute('iduser'));

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");

    exit();
});

?>