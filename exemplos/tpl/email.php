<?php 

	require_once("vendor/autoload.php");

	// namespace
	use Rain\Tpl;

	// config
	$config = array(
	    "tpl_dir"       => "templates/",
	    "cache_dir"     => "cache/",
	    "debug"         => true, // set to false to improve the speed
	);

	Tpl::configure( $config );
	
	// create the Tpl object
	$tpl = new Tpl;
	
	// assign a variable
	$tpl->assign( "name", "Nome Teste" );
	$tpl->assign( "version", PHP_VERSION );
	
	// assign an array
	//$tpl->assign( "week", array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ) );
	
	// draw the template
	$html = $tpl->draw( "index", true );

	//Create a new PHPMailer instance
	//Criado uma nova instancia de PHPMailer
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	//Especificar ao PHPMailer que vai usar SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	//Habilitar o debugging do SMTP
	// 0 = off (for production use)
	// 0 = desligado (usar para produção)
	// 1 = client messages
	// 1 = mensagens de cliente
	// 2 = client and server messages
	// 2= mensagens de cliente e servidor
	$mail->SMTPDebug = 2;

	//Ask for HTML-friendly debug output
	//Debug em HTML
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	//Informar o nome do servidor de e-mail
	$mail->Host = 'smtp.gmail.com';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	// se sua rede não suportar SMTP com IPv6

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	//Informar o número da porta SMTP - 587 para autenticação TLS, conforme a.k.a RFC4409 SMTP
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	//Definir a encriptação que o sistema vai usar - ssl (depreciado) ou tls
	$mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	//Se utilizar o método de autenticação SMTP
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	//Username para a autenticação SMTP - use o e-mail completo para o Gmail
	$mail->Username = "username@gmail.com";

	//Password to use for SMTP authentication
	//Senha para usar na autenticação SMTP
	$mail->Password = "yourpassword";

	//Set who the message is to be sent from
	//Definir quem está enviando a mensagem
	$mail->setFrom('from@example.com', 'First Last');

	//Set an alternative reply-to address
	//Definir um endereço de email alternativo
	$mail->addReplyTo('replyto@example.com', 'First Last');

	//Set who the message is to be sent to
	//Definir para quem a mensagem vai ser enviada
	$mail->addAddress('whoto@example.com', 'John Doe');

	//Set the subject line
	//Definir o assunto
	$mail->Subject = 'PHPMailer GMail SMTP test';

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//Lenfo a mensagem de um arquivo de HTML externo, converte as imagens referenciadas para incorporado
	//convert HTML into a basic plain-text alternative body
	//converte um HTML simplres em um texto basico para alternativa do corpo
	$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

	//Replace the plain text body with one created manually
	//Substitui um corpo de texto simples por um criado manualmente
	$mail->AltBody = 'This is a plain-text message body';

	//Attach an image file
	//Anexa um arquivo de imagem
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	//enviar a mensagem, checagem de erros
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "Message sent!";
	}


 ?>