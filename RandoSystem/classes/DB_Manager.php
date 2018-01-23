<?php

class DB_Manager {
	var $dbconn;
	
	function __construct() {
		set_error_handler(array(&$this, "catchError"));
		try {
			$this->dbconn = pg_connect(self::pg_connection_string_from_database_url());
		} Catch (Exception $e) {
			Echo $e->getMessage();
		}
	}

	function catchError($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}
	

	
	function pg_connection_string_from_database_url() {
		$evars = array("user" => "postgres", 
						"pass" => "password",
						"host" => "some-postgres",
						"port" => "5432",
						"db" => "randosystem");
		extract($evars);
		//extract(parse_url($_ENV["DATABASE_URL"])); // only for heroku
		//$db = substr($path, 1); // only for heroku
		//return "user=$user password=$pass host=$host sslmode=require dbname=" . $db;
		return "user=$user password=$pass host=$host sslmode=disable dbname=" . $db;
	}
	
	function open() {
		$this->dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
	}
	
	function close() {
		return pg_close($this->dbconn);
	}
	
	function query($sql) {
		return pg_query($this->dbconn, $sql);
	}
	
	function numrows($query) {
		return pg_numrows($query);
	}
	
	function fetch_array($query, $row = NULL) {
		return pg_fetch_array($query, $row);
	}
	
	function fetch_object($query, $row = NULL) {
		return pg_fetch_object($query, $row);
	}
	

}

?>