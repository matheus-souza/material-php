<?php 

	spl_autoload_register(function($nameClass) {
		$nameClass = str_replace('\\', DIRECTORY_SEPARATOR, $nameClass);

		//Caminho das nossas classes
		$dirClass = "class";
		//Path do arquivo
		$filename = $dirClass.DIRECTORY_SEPARATOR.$nameClass.".php";

		if (file_exists($filename)) {
			require_once($filename);
		}
	});


 ?>