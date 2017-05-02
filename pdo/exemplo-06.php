<?php 

	$conn = new PDO("mysql:dbname=dbphp7;host=127.0.0.1", "root", "");
	
	$conn->beginTransaction();

	$stmt = $conn->prepare("DELETE FROM tb_usuarios WHERE idusuario = ?");

	$id = 1;

	$stmt->execute(array($id));

	//$conn->rollback();

	$conn->commit();

	echo "Dados deletados com sucesso";

 ?>