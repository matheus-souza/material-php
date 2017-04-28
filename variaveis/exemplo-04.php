<?php 
	//tipagem automatica
	$nome = $_GET["a"];
	var_dump($nome);

	//tipagem forte
	$nome = (int)$_GET["b"];
	var_dump($nome);

	//pegar ip do ambiente
	$ip = $_SERVER["REMOTE_ADDR"];
	echo $ip;

	
	$ip = $_SERVER["SCRIPT_NAME"];
	echo $ip;	
 ?>