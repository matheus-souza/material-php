<?php 
	require_once("config.php");

	use Cliente\Cadastro;


	$cad = new Cadastro();

	$cad->setNome("Matheus");
	$cad->setEmail("mail@mail.com");
	$cad->setSenha("senha");

	echo $cad->registrarVenda();

 ?>