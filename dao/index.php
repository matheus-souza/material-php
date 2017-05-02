<?php 

	require_once("config.php");

	//exibe um usuário
	//$root = new Usuario();
	
	//$root->loadById(3);
	//echo $root;

	//exibe lista
	//$lista = Usuario::getList();

	//echo json_encode($lista);

	//Carrega uma lista de usuários pelo login
	//$search = Usuario::search("jo");

	//echo json_encode($search);

	//carrega um usuario pelo login e senha
	$usuario = new Usuario();
	$usuario->login("root", "!@#$");

	echo $usuario;

 ?>