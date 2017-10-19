<?php 

	$conn = new mysqli("db", "root", "root", "dbphp7");

	if ($conn->connect_error) {
		echo "Error: ".$conn->connect_error;
	}

	$stmt = $conn->prepare("INSERT INTO tb_usuarios (deslogin, dessenha) VALUES(?,?)");

	$stmt->bind_param("ss", $login, $senha);

	$login = "user";
	$senha = "12345";

	$stmt->execute();

	$login = "root";
	$senha = "!@#$";

	$stmt->execute();

 ?>