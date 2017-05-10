<?php 
	
	$data = array(
		"empresa" => "Empresa Nova"
	);

	setcookie("NOME_COOKIE", json_encode($data), time() + 3600);

	echo "OK";

 ?>