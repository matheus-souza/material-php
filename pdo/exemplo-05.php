<?php 

	$conn = new PDO("mysql:dbname=dbphp7;host=db", "root", "root");
	
	$stmt = $conn->prepare("DELETE FROM tb_usuarios WHERE idusuario = :ID");

	$id = 2;

	$stmt->bindParam(":ID", $id);

	$stmt->execute();

	echo "Dados deletados com sucesso";

 ?>