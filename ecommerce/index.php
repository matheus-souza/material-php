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
    use \Models\Order;
    use \Models\OrderStatus;

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

        $address = new Address();
        $cart = Cart::getFromSession();

        if (isset($_GET['zipcode'])) {
            $address->loadFromCep($_GET['zipcode']);

            $cart->setdeszipcode($_GET['zipcode']);

            $cart->save();

            $cart->getCalculateTotal();
        }

        if (is_null($address->getdesaddress())) $address->setdesaddress('');
        if (is_null($address->getdescomplement())) $address->setdescomplement('');
        if (is_null($address->getdesdistrict())) $address->setdesdistrict('');
        if (is_null($address->getdescity())) $address->setdescity('');
        if (is_null($address->getdesstate())) $address->setdesstate('');
        if (is_null($address->getdescountry())) $address->setdescountry('');
        if (is_null($address->getdeszipcode())) $address->setdeszipcode('');

        $page = new Page();

        $page->setTpl('checkout', [
            'cart' => $cart->getValues(),
            'address' => $address->getValues(),
            'products' => $cart->getProducts(),
            'error' => Address::getMsgError(),
        ]);
    });

    $app->post('/checkout', function () {
        User::verifyLogin(false);

        if (!isset($_POST['zipcode']) || $_POST['zipcode'] === '') {
            Address::setMsgError("Informe o CEP.");
            header("Location: /checkout");
            exit();
        }
        if (!isset($_POST['desaddress']) || $_POST['desaddress'] === '') {
            Address::setMsgError("Informe o endereço.");
            header("Location: /checkout");
            exit();
        }
        if (!isset($_POST['desdistrict']) || $_POST['desdistrict'] === '') {
            Address::setMsgError("Informe o bairro.");
            header("Location: /checkout");
            exit();
        }
        if (!isset($_POST['descity']) || $_POST['descity'] === '') {
            Address::setMsgError("Informe a cidade.");
            header("Location: /checkout");
            exit();
        }
        if (!isset($_POST['desstate']) || $_POST['desstate'] === '') {
            Address::setMsgError("Informe o estado.");
            header("Location: /checkout");
            exit();
        }
        if (!isset($_POST['descountry']) || $_POST['descountry'] === '') {
            Address::setMsgError("Informe o país.");
            header("Location: /checkout");
            exit();
        }

        $user = User::getFromSession();

        $address = new Address();

        $_POST['deszipcode'] = $_POST['zipcode'];
        $_POST['idperson'] = $user->getidperson();

        $address->setData($_POST);

        $address->save();

        $cart = Cart::getFromSession();

        $totals = $cart->getCalculateTotal();

        $order = new Order();
        $order->setData([
            'idcart' => $cart->getidcart(),
            'iduser' => $user->getiduser(),
            'idstatus' => OrderStatus::EM_ABERTO,
            'idaddress' => $address->getidaddress(),
            'vltotal' => $totals['vlprice'] + $cart->getvlfreight()
        ]);

        $order->save();

        header("Location: /order/".$order->getidorder());
        exit();
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

    $app->post('/profile', function () {
        User::verifyLogin(false);

        if (!isset($_POST['desperson']) || $_POST['desperson'] === '') {
            User::setMsgError("Preencha o seu nome.");
            header("Location: /profile");
            exit();
        }

        if (!isset($_POST['desemail']) || $_POST['desemail'] === '') {
            User::setMsgError("Preencha o e-mail.");
            header("Location: /profile");
            exit();
        }

        if (!isset($_POST['nrphone']) || $_POST['nrphone'] === '') {
            User::setMsgError("Preencha o telefone.");
            header("Location: /profile");
            exit();
        }

        $user = User::getFromSession();

        if ($_POST['desemail'] !== $user->getdesemail()) {
            if (User::checkLoginExist($_POST['desemail'])) {
                User::setMsgError("Este endereço de e-mail já está cadastrado.");
                header("Location: /profile");
                exit();
            }
        }

        $_POST['inadmin'] = $user->getinadmin();
        $_POST['despassword'] = $user->getdespassword();
        $_POST['deslogin'] = $_POST['desemail'];

        $user->setData($_POST);

        $user->update();

        User::setMsgSuccess("Dados salvos com sucesso!");

        $_SESSION[User::SESSION] = $user->getValues();

        header("Location: /profile");
        exit();
    });

    $app->get('/order/{idorder}', function (Request $request) {
        User::verifyLogin(false);

        $order = new Order();
        $order->get((int)$request->getAttribute('idorder'));

        $page = new Page();

        $page->setTpl('payment', [
            'order' => $order->getValues()
        ]);
    });

    $app->get('/boleto/{idorder}', function (Request $request) {

        User::verifyLogin(false);

        $order = new Order();
        $order->get((int)$request->getAttribute('idorder'));


        // DADOS DO BOLETO PARA O SEU CLIENTE
        $dias_de_prazo_para_pagamento = 5;
        $taxa_boleto = 2.95;
        $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
        $valor_cobrado = formatPrice($order->getvltotal()); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

        $dadosboleto["nosso_numero"] = $order->getidorder();  // Nosso numero - REGRA: Máximo de 8 caracteres!
        $dadosboleto["numero_documento"] = $order->getidorder();	// Num do pedido ou nosso numero
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da vírgula

        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $order->getdesperson();
        $dadosboleto["endereco1"] = $order->getdesaddress() . ' ' . $order->getdesdistrict();
        $dadosboleto["endereco2"] = $order->getdescity() . ' - ' . $order->getdesstate() . ' - ' . $order->getdescountry() . ' -  CEP: '. $order->getdeszipcode();

        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
        $dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
        $dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
        $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
        $dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
        $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
        $dadosboleto["instrucoes4"] = "- Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "";
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "";


        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


        // DADOS DA SUA CONTA - ITAÚ
        $dadosboleto["agencia"] = "1565"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "13877";	// Num da conta, sem digito
        $dadosboleto["conta_dv"] = "4"; 	// Digito do Num da conta

        // DADOS PERSONALIZADOS - ITAÚ
        $dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

        // SEUS DADOS
        $dadosboleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
        $dadosboleto["cpf_cnpj"] = "";
        $dadosboleto["endereco"] = "Coloque o endereço da sua empresa aqui";
        $dadosboleto["cidade_uf"] = "Cidade / Estado";
        $dadosboleto["cedente"] = "Coloque a Razão Social da sua empresa aqui";

        // NÃO ALTERAR!
        $path = $_SERVER['DOCUMENT_ROOT'] .
                DIRECTORY_SEPARATOR .
                'res' . DIRECTORY_SEPARATOR .
                'boletophp' . DIRECTORY_SEPARATOR .
                'include' . DIRECTORY_SEPARATOR;

        require_once ($path . 'funcoes_itau.php');
        require_once ($path . 'layout_itau.php');
    });

$app->run();

 ?>