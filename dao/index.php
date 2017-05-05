<?php 
	require_once("config.php");

//exibe um usuário
/*
	$root = new Usuario();
	
	$root->loadById(1);
	echo $root;
*/

//exibe lista
/*
	$lista = Usuario::getList();

	echo json_encode($lista);
*/

//Carrega uma lista de usuários pelo login
/*
	$search = Usuario::search("jo");

	echo json_encode($search);
*/

//carrega um usuario pelo login e senha
/*
	$usuario = new Usuario();
	$usuario->login("root", "!@#$");

	echo $usuario;
*/

//insere novo usuario
/*
	$aluno = new Usuario();

	$aluno->setDeslogin("aluno");
	$aluno->setDessenha("@lun0");

	$aluno->insert();

	echo $aluno;
*/

//atualizando usuario
/*
	$usuario = new Usuario();

	$usuario->loadById(2);

	$usuario->update("alterado", "altrede");

	echo $usuario;
*/

//exclui um usuario

	$usuario = new Usuario();

	$usuario->loadById(5);

	$usuario->delete();

	echo $usuario;

 ?>