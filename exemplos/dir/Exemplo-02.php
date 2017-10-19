<?php 

	$images = scandir("images");

	$data = array();

	foreach ($images as $image) {
		if (!in_array($image, array(".", ".."))) {
			//pega o diretório da imagem
			$filename = "images".DIRECTORY_SEPARATOR.$image;
			//pega o caminho do arquivo
			$info = pathinfo($filename);
			//pega o tamanho do arquivo
			$info["size"] = filesize($filename);
			//pega data da ultima modificação
			$info["modified"] = date("d/m/Y H:i:s", filemtime($filename));
			//disponibiliza a imagem por url
			$info["url"] = "http://localhost:15000/curso-php/dir/".$filename;

			//adiciona o array info no array data
			array_push($data, $info);
		}
	}

	echo json_encode($data);
 ?>