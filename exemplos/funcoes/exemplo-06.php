<?php 

	$pessoa = array(
		'nome' => 'Matheus',
		'idade' => 19
	);

	foreach ($pessoa as &$value) {
		if (gettype($value) === 'integer') $value += 10;

		echo $value."<br>";
	}

	print_r($pessoa);

 ?>