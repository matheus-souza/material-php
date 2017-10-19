<?php 

	function soma(float ...$valores):float {
		return array_sum($valores);
	}

	echo soma(2, 2);
	echo "<br>";
	echo soma(2.5, 2);
	echo "<br>";
	echo var_dump(soma(10, 20));

 ?>