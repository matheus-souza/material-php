<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
	use \Slim\Http\Request;
	use \Db\Sql;
	use \Page\Page;
	use \Page\PageAdmin;
	use \Models\User;

	$app = new App;

	$app->get('/', function () {
		
		$page = new Page();

		$page->setTpl("index");
	});

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

    $app->get('/admin/users', function () {
        User::verifyLogin();

        $users = User::listAll();

        $page = new PageAdmin();

        $page->setTpl("users", array(
            "users" => $users
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

	$app->run();

 ?>