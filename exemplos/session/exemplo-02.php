<?php 

	require_once 'config.php';

	//apaga variavel de sessão
	session_unset($_SESSION['nome']);

	echo $_SESSION['nome'];

	//limpa variavel e remove usuario
	session_destroy();

 ?>