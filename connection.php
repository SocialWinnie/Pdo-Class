<?php
	
	namespace test\pdo;

	use \PDO;

	// require_once("../config/config.php");

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
		private $data;

		function __construct()
		{

			// 宣告
			$dbchkConfig = unserialize(dbchkConfig);

			// 初始化
			$this->dbSelect     = $dbchkConfig['dbSelect'];
			$this->dbHost       = $dbchkConfig['dbHost'];
			$this->dbPort       = $dbchkConfig['dbPort'];
			$this->dbName       = $dbchkConfig['dbName'];
			$this->userAccount  = $dbchkConfig['userAccount'];
			$this->userPassword = $dbchkConfig['userPassword'];

			$dsn = "{$this->dbSelect}:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName}";

			try {

				$this->dbWork = new PDO($dsn, $this->userAccount, $this->userPassword);

			} catch (PDOException $e) {

				var_dump($e);

			}

			$this->dbWork->query('SET NAMES utf8');
			// 指定PDO錯誤模式和錯誤處理
			$this->dbWork->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}

		function __destruct() 
		{

			$this->dbWork = null;

		}

		public function db_select($sql)
		{

			$dbReady = $this->dbWork->query($sql);
			$this->data = $dbReady->fetchAll(PDO::FETCH_CLASS);

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

?>