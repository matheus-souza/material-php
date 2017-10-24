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
	$matheus->nome = "Nome Souza";
	echo $matheus->falar();
	
 ?>