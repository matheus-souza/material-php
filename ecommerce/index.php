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

 ?>