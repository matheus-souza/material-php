<?php 

	$conn = new PDO("mysql:dbname=dbphp7;host=db", "root", "root");
	
	$stmt = $conn->prepare("INSERT INTO tb_usuarios (deslogin, dessenha) VALUES (:LOGIN, :PASSWORD)");

	$login = "José";
	$password = "123qwe";

	$stmt->bindParam(":LOGIN", $login);
	$stmt->bindParam(":PASSWORD", $password);

	$stmt->execute();

	echo "Inserido com sucesso";


 ?>