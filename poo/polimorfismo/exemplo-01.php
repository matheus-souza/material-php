<?php 

	abstract class Animal {
		public function falar() {
			return "Som";
		}

		public function mover() {
			return "Andar";
		}
	}

	class Cachorro extends Animal {
		public function falar() {
			return "Late";
		}
	}

	class Gato extends Animal {
		public function falar() {
			return "Mia";
		}
	}

	class Passaro extends Animal {
		public function falar() {
			return "Canta";
		}

		public function mover() {
			return "Voa e ".parent::mover();
		}
	}

	$pluto = new Cachorro();

	echo get_class($pluto)."<br>";	
	echo $pluto->falar()."<br>";
	echo $pluto->mover()."<br>";
	
	echo "<br>";

	$garfield = new Gato();

	echo get_class($garfield)."<br>";
	echo $garfield->falar()."<br>";
	echo $garfield->mover()."<br>";

	echo "<br>";

	$passaro = new Passaro();

	echo get_class($passaro)."<br>";
	echo $passaro->falar()."<br>";
	echo $passaro->mover()."<br>";

 ?>