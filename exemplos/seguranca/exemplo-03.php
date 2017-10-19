<?php 

	$pasta = "arquivos";
	$permissao = "0775";

	if (!is_dir($pasta)) mkdir($pasta, $permissao);

	echo "Diretório criado com sucesso";

	//0-nenhuma permissão
	//1-permissão de execução
	//2-permissão gravação
	//3-permissão de execução e gravação
	//4-permissão de leitura
	//5-permissão de leitura e execução
	//6-permissão de leitura e gravação
	//7-permissão de leitura, gravação e execução

	//x--=>root
	//-x-=>grupo root
	//--x=>todos usuarios

 ?>