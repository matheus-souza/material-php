<?php 

	//Conectando com SQLServer
	$conn = new PDO("sqlsrv:Server=db_sqlserver;Database=dbphp7;ConnectionPooling=0", "SA", "Root9876");

	$stmt = $conn->prepare("SELECT * FROM tb_usuarios ORDER BY deslogin");

	$stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($results as $row) {
		foreach ($row as $key => $value) {
			echo "<strong>".$key.":</strong>".$value."<br/>";
		}
		echo "=================================<br>";
	}

 ?>