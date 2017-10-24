<?php 

	$nome = "Nome";
	$sobrenome = "Sobrenome";

	//concatenando variaveis
	$nomeCompleto = $nome." ".$sobrenome;

	echo $nomeCompleto;

	echo $nome;

	echo "<br/>";

	//apaga a variavel
	unset($nome);

	//isset verifica se a variavel existe
	if (isset($nome)) {
		echo $nome;
	}
 ?>