<?php 

	//parametro que não tem valor padrão colocar sempre na esquerda
	function ola($texto="mundo", $periodo="Bom dia") {
		return "Olá $texto! $periodo!<br>";
	}

	echo ola("Nome");
	echo ola();
	echo ola("Teste", "Teste");

 ?>