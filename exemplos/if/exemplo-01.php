<?php

	$suaIdade = 30;

	$idadeCrianca = 12;
	$idadeMaior = 18;
	$idadeMelhor = 65; 

	if ($suaIdade < $idadeCrianca) {
		echo "É criança";
	} else if ($suaIdade < $idadeMaior) {
		echo "É adolescente";
	} else if ($suaIdade < $idadeMelhor) {
		echo "É adulto";
	} else {
		echo "É idoso";
	}

	echo "<br>";

	echo ($suaIdade < $idadeMaior)?"Menor de idade":"Maior idade";
 ?>