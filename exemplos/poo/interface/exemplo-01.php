<?php 

	interface Veiculo {
		public function acelerar($velocidade);
		public function frenar($velocidade);
		public function trocaMarcha($marcha);

	}

	class Civic implements Veiculo {
		public function acelerar($velocidade) {
			echo "O veiculo acelerou ".$velocidade." KM/h";
		}

		public function frenar($velocidade) {
			echo "O veiculo frenou ".$velocidade." KM/h";

		}

		public function trocaMarcha($marcha) {
			echo "O veiculo engatou marcha ".$marcha;

		}
	}


	$carro = new Civic();
	$carro->trocaMarcha(1);

 ?>