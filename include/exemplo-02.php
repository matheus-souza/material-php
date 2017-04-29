<?php 

	//include 'exemplo-01.php';
	
	//melhor, usar sempre
	//require 'exemplo-01.php';

	//evita de chamar mais de uma vez o arquivo
	require_once 'inc/exemplo-01.php';

	$resultado = somar(10, 20);

	echo $resultado;

 ?>