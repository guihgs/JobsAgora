<?php
//Class to config Database
class Database{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

	//Properties
	//DB hander
	private $dbh;
	private $error;
	private $stmt;

	public function __construct(){
		//Set DSN 
		$dsn = 'mysql:host='.$this->host .';dbname='.$this->dbname;

		//Set Options
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		//PDO Instance
		try{
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}catch(PDOException $e){
			$this->error = $e->getMessage();
		}
	}
	//Query for database
	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}
	public function bind($param, $value, $type = null){
		if(is_null($type)){
			switch(true){
				case is_int($value) : 
				$type = PDO::PARAM_INT;
				break;
				case is_bool($value) : 
				$type = PDO::PARAM_BOOL;
				break;
				case is_null($value) : 
				$type = PDO::PARAM_NULL;
				break;
				default : 
				$type = PDO::PARAM_STR;
				break;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	//Execute the query
	public function execute(){
		return $this->stmt->execute();
	}
	//Fetch Result query
	public function resultSet(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	//Fetch Result Single Query
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}
}