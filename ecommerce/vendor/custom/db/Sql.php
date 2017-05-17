<?php 
	namespace db;
	class Sql{
		const HOSTNAME = "db_ecommerce";
		const USERNAME = "root";
		const PASSWORD = "root";
		const DBNAME = "db_ecommerce";
		private $conn;
		public function __construct()
		{
			try {
				$this->conn = new \PDO(
					"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME,
					Sql::USERNAME,
					Sql::PASSWORD
				);
			} catch (Exception $e) {
				throw new PDOException($e);
			}
		}
		private function setParams($statement, $parameters=array())
		{
			foreach ($parameters as $key => $value) {
				$this->bindParam($statement, $key, $value);
			}
		}
		private function bindParam($statement, $key, $value)
		{
			$statement->bindParam($key, $value);
		}
		public function query($rawQuery, $params = array())
		{
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);
			$stmt->execute();
		}
		public function select($rawQuery, $params = array()):array
		{
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);
			$stmt->execute();
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
	}
?>