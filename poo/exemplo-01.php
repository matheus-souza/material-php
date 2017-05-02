<?php 
	
	class Pessoa {

		//atributo
		public $nome;

		//método
		public function falar() {
			return "Meu nome é ".$this->nome;
		}
	}

	$matheus = new Pessoa();
	$matheus->nome = "Matheus Souza";
	echo $matheus->falar();
	
 ?>