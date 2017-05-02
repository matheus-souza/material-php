<?php 
	
	//Conectando com SQLServer
	$conn = new PDO("sqlsrv:Database=dbphp7;server=127.0.0.1;ConnectionPooling=0", "sa", "@souza123");

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