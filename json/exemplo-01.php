<?php 
	
	$pessoas = array();

	array_push($pessoas, array(
		'nome'=>'João',
		'idade' => 20
	));

	array_push($pessoas, array(
		'nome'=>'Matheus',
		'idade' => 19
	));

	echo json_encode($pessoas);


 ?>