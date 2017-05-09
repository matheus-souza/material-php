<?php 

	//Classe para acesso e funções no banco
	class Sql extends PDO {
		private $conn;

		//Conecta com banco de dados toda vez que instancia a classe
		public function __construct() {
			$this->conn = new PDO("mysql:dbname=dbphp7;host=db", "root", "root");
		}

		/**
		 * Recebe 2 paramentros
		 * statement = o statement declarao
		 * parameters = dados
		 */
		private function setParams($statement, $parameters = array()) {
			//faz o bind de todos os parametros
			foreach ($parameters as $key => $value) {
				//chama setParam passando os valores
				$this->setParam($statement, $key, $value);
			}
		}

		//Faz o bind do dado passado
		private function setParam($statement, $key, $value) {
			$statement->bindParam($key, $value);
		}

		/**
		 * O método recebe 2 parametros
		 * rawQuery = é o comando SQL bruto
		 * params = dados que serão recebidos
		 */
		public function query($rawQuery, $params = array()) {
			//Cria o statemant
			$stmt = $this->conn->prepare($rawQuery);

			//faz o set/bind de cada parametro
			$this->setParams($stmt, $params);

			$stmt->execute();
			
			return $stmt;
		}

		//faz select no banco, e retorna obrigatóriamente um array
		public function select($rawQuery, $params = array()):array {
			//recebe o statemant do return do método query
			$stmt = $this->query($rawQuery, $params);

			//retorna os dados recuperados do banco de dados
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

 ?>