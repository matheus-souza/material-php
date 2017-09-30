<?php

	use \Psr\Http\Message\ResponseInterface as Response;

    session_start();
	require_once("vendor/autoload.php");

	use \Slim\App;
    use \Slim\Http\Request;
	use \Page\Page;
    use \Models\Product;
    use \Models\Cart;
    use \Models\User;
    use \Models\Address;

	$app = new App;

	require_once "site-categories.php";
    require_once "site-producst.php";
    require_once "admin.php";
    require_once "admin-users.php";
    require_once "admin-login.php";
    require_once "admin-categories.php";
    require_once "admin-products.php";
    require_once "functions.php";

    $app->get('/', function () {

        $products = Product::listAll();

        $page = new Page();

        $page->setTpl("index", array(
            'products' => Product::checkList($products)
        ));
    });

    $app->get('/cart', function () {
        $cart = Cart::getFromSession();

        $page = new Page();

        $page->setTpl('cart', [
            'cart' => $cart->getValues(),
            'products' => $cart->getProducts(),
            'error' => Cart::getMsgError()
        ]);
    });

    $app->get('/cart/{idproduct}/add', function (Request $request) {
        $product = new Product();

        $product->get((int)$request->getAttribute('idproduct'));

        $cart = Cart::getFromSession();

        $qtd = $_GET['qtd'] ?? 1;

        for ($i = 0; $i < $qtd; $i++) {
            $cart->addProduct($product);
        }
        
        header("Location: /cart");
        exit();
    });

    $app->get('/cart/{idproduct}/minus', function (Request $request) {
        $product = new Product();

        $product->get((int)$request->getAttribute('idproduct'));

        $cart = Cart::getFromSession();

        $cart->removeProduct($product);

        header("Location: /cart");
        exit();
    });

    $app->get('/cart/{idproduct}/remove', function (Request $request) {
        $product = new Product();

        $product->get((int)$request->getAttribute('idproduct'));

        $cart = Cart::getFromSession();

        $cart->removeProduct($product, true);

        header("Location: /cart");
        exit();
    });

    $app->post('/cart/freight', function () {
        $cart = Cart::getFromSession();

        $cart->setFreight($_POST['zipcode']);

        header("Location: /cart");
        exit();
    });

    $app->get('/checkout', function () {
        User::verifyLogin(false);

        $cart = Cart::getFromSession();

        $address = new Address();

        $page = new Page();

        $page->setTpl('checkout', [
            'cart' => $cart->getValues(),
            'address' => $address->getValues()
        ]);
    });

    $app->get('/login', function () {
        $page = new Page();

        $page->setTpl('login', [
            'error' => User::getMsgError(),
            'errorRegister' => User::getMsgRegisterError(),
            'registerValues' => $_SESSION['registerValues'] ?? ['name' => '', 'email' => '', 'phone' => '']
        ]);
    });

    $app->post('/login', function () {
        try {
            User::login($_POST['login'], $_POST['password']);
        } catch (Exception $e) {
            User::setMsgError($e->getMessage());
        }

        header("Location: /checkout");
        exit();
    });

    $app->get('/logout', function () {
        User::logout();

        header("Location: /login");
        exit();
    });

    $app->post('/register', function () {

        $_SESSION['registerValues'] = $_POST;

        if (!isset($_POST['name']) || $_POST['name'] == '') {
            User::setMsgRegisterError("Preencha o seu nome");

            header("Location: /login");
            exit();
        }

        if (!isset($_POST['email']) || $_POST['email'] == '') {
            User::setMsgRegisterError("Preencha o seu e-mail");

            header("Location: /login");
            exit();
        }

        if (!isset($_POST['password']) || $_POST['password'] == '') {
            User::setMsgRegisterError("Preencha a senha");

            header("Location: /login");
            exit();
        }

        if (!isset($_POST['phone']) || $_POST['phone'] == '') {
            User::setMsgRegisterError("Preencha o telefone");

            header("Location: /login");
            exit();
        }

        if (User::checkLoginExist($_POST['email'])) {
            User::setMsgRegisterError("Este endereço de e-mail já está sendo utilizado por outro usuário");

            header("Location: /login");
            exit();
        }

        $user = new User();

        $user->setData([
            'inadmin' => 0,
            'deslogin' => $_POST['email'],
            'desperson' => $_POST['name'],
            'desemail' => $_POST['email'],
            'despassword' => $_POST['password'],
            'nrphone' => $_POST['phone']
        ]);

        $user->save();

        User::login($_POST['email'], $_POST['password']);

        header("Location: /checkout");
        exit();
    });

    $app->get('/forgot', function () {
        $page = new Page();

        $page->setTpl("forgot");
    });

    $app->post('/forgot', function () {
        $user = User::getForgot($_POST["email"], false);

        header("Location: /forgot/sent");
        exit();
    });

    $app->get('/forgot/sent', function () {
        $page = new Page();

        $page->setTpl("forgot-sent");
    });

    $app->get('/forgot/reset', function () {
        $user = User::validForgotDecryp($_GET["code"]);

        $page = new Page();

        $page->setTpl("forgot-reset", array(
            "name" => $user["desperson"],
            "code" => $_GET["code"]
        ));
    });

    $app->post('/forgot/reset', function () {
        $forgot = User::validForgotDecryp($_POST["code"]);

        User::setForgotUsed($forgot["idrecovery"]);

        $user = new User();

        $user->get((int)$forgot["iduser"]);

        $user->setPassword($_POST["password"]);

        $page = new Page();

        $page->setTpl("forgot-reset-success");
    });

    $app->get('/profile', function () {
        User::verifyLogin(false);

        $user = User::getFromSession();

        $page = new Page();

        $page->setTpl("profile", [
            'user' => $user->getValues(),
            'profileMsg' => User::getMsgSuccess(),
            'profileError' => User::getMsgError()
        ]);
    });

$app->run();

 ?>