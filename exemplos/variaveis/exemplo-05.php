<?php 

	$nome = "Matheus";

	function teste() {
		global $nome;
		echo $nome;
	}
	function teste2() {
		$nome = "Exemplo";
		echo $nome." t2";
	}

	teste();
	teste2();
 ?>