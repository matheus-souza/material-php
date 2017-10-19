<?php 
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
	
	require_once("vendor/autoload.php");

	$app = new \Slim\App;

	$app->get('/', function () {
		echo "Home Page";
		echo "<br>";
		echo json_encode(array(
			'date'=>date("Y-m-d H:i:s")
		));
	});

	$app->get('/hello/{name}', function (Request $request, Response $response) {
	    $name = $request->getAttribute('name');
	    $response->getBody()->write("Hello, $name");

	    return $response;
	});

	$app->run();

 ?>