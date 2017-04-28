<?php 

	/*VARIAVEIS BASICAS*/
	//String
	$nome = "Matheus";
	$site = 'www.matheush.com';
	//int
	$ano = 1998;
	//float
	$salario = 1250.90;
	//boolean
	$bloqueado = false;

	/*TIPO COMPOSTO*/
	//array
	$frutas = array("abacaxi", "laranja", "maça");
	echo $frutas[2];

	//objeto
	$nascimento = new DateTime();
	var_dump($nascimento);

	/*TIPO ESPECIAL*/
	//arquico
	$arquivo = fopen("exemplo-03.php", "r");
	var_dump($arquivo);

	//nulo
	$nulo = null;

 ?>