<?php 
	/**
	 * Carrega as classes de outros diretórios
	 * Na spl_autoload_register pode ser passado uma string com uma função
	 * ou uma função anonima
	 */
	function incluirClasses($nomeClasse) {
		if (file_exists($nomeClasse.".php") === true) {
			require_once($nomeClasse.".php");
		}
	}
	spl_autoload_register("incluirClasses");
	spl_autoload_register(function($nomeClasse) {
		if (file_exists("abstratas".DIRECTORY_SEPARATOR.$nomeClasse.".php") === true) {
			require_once("abstratas".DIRECTORY_SEPARATOR.$nomeClasse.".php");
		}
	});

	$carro = new DelRey();
	$carro->acelerar(200);

 ?>