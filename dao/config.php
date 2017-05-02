<?php 

	spl_autoload_register(function($nomeClasse) {
		if (file_exists("class".DIRECTORY_SEPARATOR.$nomeClasse.".php") === true) {
			require_once("class".DIRECTORY_SEPARATOR.$nomeClasse.".php");
		}
	});

 ?>