<?php 

	if (isset($_COOKIE["NOME_COOKIE"])) {
		$obj = json_decode($_COOKIE["NOME_COOKIE"]);

		echo $obj->empresa;
	}

 ?>