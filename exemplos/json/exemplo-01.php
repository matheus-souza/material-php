<?php 
	
	$pessoas = array();

	array_push($pessoas, array(
		'nome'=>'João',
		'idade' => 20
	));

	array_push($pessoas, array(
		'nome'=>'Nome',
		'idade' => 19
	));

	echo json_encode($pessoas);


 ?>