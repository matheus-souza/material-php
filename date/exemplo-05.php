<?php 
	
	$dt = new DateTime();
	
	echo $dt->format("d/m/Y H:i:s")."<br>";

	$periodo = new DateInterval("P15D");
	
	$dt->add($periodo);

	echo $dt->format("d/m/Y H:i:s");
	
?>