<?php 

	$name = "images";

	if (!is_dir($name)) {
		//cria um diretório
		mkdir($name);

		echo "$name criado com sucesso";
	} else {
		//apaga diretório
		rmdir($name);
		echo "$name já existe";
	}

 ?>