<?php 

	class Sql extends PDO {
		private $conn;

		public function __construct() {
			$this->conn = new PDO("mysql:dbname=dbphp7;host=127.0.0.1", "root", "");
		}

		private function setParams($statment, $parameters = array()) {
			foreach ($parameters as $key => $value) {
				$this->setParam($key, $value);
			}
		}

		private function setParam($statment, $key, $value) {
			$statment->bindParam($key, $value);
		}

		public function query($rawQuaery, $params = array()) {
			$stmt = $this->conn->prepare($rawQuaery);

			$this->setParams($stmt, $params);

			$stmt->execute();
			
			return $stmt;
		}

		public function select($rawQuery, $params = array()):array {
			$stmt = $this->query($rawQuery, $params);

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

 ?>