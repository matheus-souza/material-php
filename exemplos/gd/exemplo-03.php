<?php 

	$image = imagecreatefromjpeg("images/certificado.jpg");

	$titleColor = imagecolorallocate($image, 0, 0, 0);
	$gray = imagecolorallocate($image, 100, 100, 100);

	imagettftext($image, 32, 0, 320, 250, $titleColor, "fonts".DIRECTORY_SEPARATOR."Bevan".DIRECTORY_SEPARATOR."Bevan-Regular.ttf", "CERTIFICADO");
	imagettftext($image, 32, 0, 400, 325, $titleColor, "fonts".DIRECTORY_SEPARATOR."Playball".DIRECTORY_SEPARATOR."Playball-Regular.ttf", utf8_decode("José Pinto"));
	imagestring($image, 3, 400, 370, utf8_decode("Concluído em: ").date("d/m/Y"), $titleColor);

	header("Content-type: image/jpeg");

	//o terceiro parametro é a porcentagem que vai ser gerada da image, pode perder qualidade
	imagejpeg($image);

	imagedestroy($image);

 ?>