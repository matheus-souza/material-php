<?php 
	/**
	 * Chama o método com a classe instanciada como argumento
	 * logo, carrega as classes automaticamente
	 * desde que as mesmas estejam na mesma pasta
	 */
	function __autoload($nomeClasse) {
		require_once("$nomeClasse.php");
	}

	$carro = new DelRey();
	$carro->acelerar(200);

 ?>