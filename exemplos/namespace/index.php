<?php 
	require_once("config.php");

	use Cliente\Cadastro;


	$cad = new Cadastro();

	$cad->setNome("Nome");
	$cad->setEmail("mail@mail.com");
	$cad->setSenha("senha");

	echo $cad->registrarVenda();

 ?>