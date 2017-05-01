<?php 

	$a = 10;

	//PASSAGEM DE VALOR POR PARAMETRO
	function trocaValor($a) {
		$a += 50;

		return $a;
	}

	//PASSAGEM DE VALOR POR REFERENCIA
	function trocaValorRef(&$a) {
		$a += 50;

		return $a;
	}

	echo $a;

	echo "<br>";

	echo trocaValor($a);

	echo "<br>";

	echo $a;

	echo "<br>Referência<br>";

	echo trocaValorRef($a);

	echo "<br>";

	echo $a;
 ?>