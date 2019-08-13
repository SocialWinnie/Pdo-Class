<?php
	
	namespace test\pdo;

	use \PDO;

	require_once("./config/config.php");

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);

	Class DbSet {

		private $dbSelect;
		private $dbHost;
		private $dbPort;
		private $dbName;
		private $userAccount;
		private $userPassword;
		private $dbWork;
		public $data;

		function __construct($dbArray = [], $check = false)
		{

			$dbConfig = (COUNT($dbArray) == 6)?$dbArray:unserialize(dbchkConfig);

			$this->dbSelect     = $dbConfig[0];
			$this->dbHost       = $dbConfig[1];
			$this->dbPort       = $dbConfig[2];
			$this->dbName       = $dbConfig[3];
			$this->userAccount  = $dbConfig[4];
			$this->userPassword = $dbConfig[5];
			
			if ($check == false) {
				$dsn = "{$this->dbSelect}:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName}";
			} else {
				$dsn = "{$this->dbSelect}:host={$this->dbHost};port={$this->dbPort};";
			}
			
			try {

				$this->dbWork = new PDO(
					$dsn,
					$this->userAccount,
					$this->userPassword,
					[
						PDO::ATTR_TIMEOUT => 3,
					]
				);

			} catch (PDOException $e) {

				var_dump($e);

			}

			$this->dbWork->query('SET NAMES utf8');
			$this->dbWork->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}

		function __destruct() 
		{

			$this->dbWork = null;

		}

		public function db_select($sql, $status = 0)
		{
			switch ($status) {
				case 1:
					$pdoFetch = PDO::FETCH_NUM;
					break;
				case 2:
				
					break;
				default:
					$pdoFetch = PDO::FETCH_CLASS;
					break;
			}

			$dbReady = $this->dbWork->query($sql);
			$this->data = $dbReady->fetchAll($pdoFetch);

			if ($this->data == false) {

				$this->data = array();

			}

			return $this->data;

		}

		public function db_update($sql)
		{
			
			$dbReady = $this->dbWork->query($sql);
			$this->data = $dbReady->execute();

			if ($this->data == false) {

				$this->data = array();

			}

			return $this->data;

		}
			
	}
	
	$test = new DbSet();
	$sql = "SELECT * FROM `dbwhere` WHERE 1";
	$data = $test->db_select($sql, 1);
	print_r($data);
?>