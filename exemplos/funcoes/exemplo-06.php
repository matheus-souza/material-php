<?php 

	$pessoa = array(
		'nome' => 'Nome',
		'idade' => 19
	);

	foreach ($pessoa as &$value) {
		if (gettype($value) === 'integer') $value += 10;

		echo $value."<br>";
	}

	print_r($pessoa);

 ?>