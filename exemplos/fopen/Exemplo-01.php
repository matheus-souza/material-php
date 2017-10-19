<?php 

	//w+ exclui tudo que tem no arquivo e abre no modo de leitura
	//$file = fopen("log.txt", "w+");
	$file = fopen("log.txt", "a+");

	fwrite($file, date("Y-m-d H:i:s")."\r\n");

	fclose($file);

	echo "Aquivo criado com sucesso";

 ?>