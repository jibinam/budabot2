<?php
   /*
   ** Author: Sebuda/Derroylo (both RK2)
   ** Description: Database Class
   ** Version: 0.6
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 21.01.2006
   ** Date(last modified): 23.11.2006
   ** 
   ** Copyright (C) 2005, 2006 Carsten Lohmann and J. Gracik
   **
   ** Licence Infos: 
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */ 

//Database Abstraction Class
class DB {
	private $type;
	private $sql;
	private $dbName;
	private $result;
	private $user;
	private $pass;
	private $host;
	private $botname;
	private $lastQuery;
	public $errorCode = 0;
	public $errorInfo;
	
	//Constructor(opens the connection to the Database)
	public function __construct($type, $dbName, $host = NULL, $user = NULL, $pass = NULL) {
		global $vars;
		$this->type = $type;
		$this->dbName = $dbName;
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->botname = strtolower($vars["name"]);
		$this->dim = $vars["dimension"];
			
		if ($type == 'Sqlite') {
			if ($host == NULL || $host == "" || $host == "localhost") {
				$this->dbName = "./data/$this->dbName";
			} else {
				$this->dbName = "$host/$this->dbName";
			}
		}
		
		$this->connect();
	}
	
	public function connect() {
		try {
			if ($type == 'Mysql') {
				$this->sql = new PDO("mysql:host=$host", $user, $pass);
				$this->query("CREATE DATABASE IF NOT EXISTS $dbName");
				$this->selectDB($dbName);
				$this->exec("SET sql_mode='NO_BACKSLASH_ESCAPES'");
				$this->exec("SET time_zone = '+00:00'");
			} else if ($type == 'Sqlite') {
				$this->sql = new PDO("sqlite:".$this->dbName); 
			}
		} catch (PDOException $e) {
			$this->errorCode = 1;
			$this->errorInfo = $e->getMessage();
		}
	}
	
	//Sends a query to the Database and gives the result back
	public function query($stmt, $type = "object") {
		$this->result = NULL;
		$stmt = str_replace("<myname>", $this->botname, $stmt);
		$stmt = str_replace("<dim>", $this->dim, $stmt);
		
		if (substr_compare($stmt, "create", 0, 6, true) == 0) {
			$this->CreateTable($stmt);
			return;
		}

		$this->lastQuery = $stmt;
      	$result = $this->sql->query($stmt);
      	
		if (is_object($result)) {
		  	if ($type == "object") {
	  			$this->result = $result->fetchALL(PDO::FETCH_OBJ);
		  	} else if ($type == "assoc") {
		  		$this->result = $result->fetchALL(PDO::FETCH_ASSOC);
		  	} else if ($type == "num") {
		  		$this->result = $result->fetchALL(PDO::FETCH_NUM);
			}
		} else {
			$this->result = NULL;
		}

		$error = $this->sql->errorInfo();
		if ($error[0] != "00000") {
			Logger::log(__FILE__, "Error msg: $error[2] in: $stmt", ERROR);
		}

		return($result);				
	}
	
	//Does Basicly the same thing just don't gives the result back(used for create table, Insert, delete etc), a bit faster as normal querys 
	public function exec($stmt) {
		$this->result = NULL;
		
		$stmt = str_replace("<myname>", $this->botname, $stmt);
		$stmt = str_replace("<dim>", $this->dim, $stmt);
		
		if (substr_compare($stmt, "create", 0, 6, true) == 0) {
			$this->CreateTable($stmt);
			return;
		}
		
		$this->lastQuery = $stmt;
      	$aff_rows = $this->sql->exec($stmt);

		$error = $this->sql->errorInfo();
		if ($error[0] != "00000") {
			Logger::log(__FILE__, "Error msg: $error[2] in: $stmt", ERROR);
		}

		return($aff_rows);		
	}

	//Function for creating the table. Main reason is that some SQL commands are not compatible with sqlite for example the autoincrement field
	private function CreateTable($stmt) {
		if ($this->type == "Mysql") {
            $stmt = str_ireplace("AUTOINCREMENT", "AUTO_INCREMENT", $stmt);
        } else if ($this->type == "Sqlite") {
            $stmt = str_ireplace("AUTO_INCREMENT", "AUTOINCREMENT", $stmt);
			$stmt = str_ireplace(" INT ", " INTEGER ", $stmt);
        }
		
		$this->lastQuery = $stmt;
		$this->sql->exec($stmt);

		$error = $this->sql->errorInfo();
		if ($error[0] != "00000") {
			Logger::log(__FILE__, "Error msg: $error[2] in: $stmt", ERROR);
		}
	}

	//Switch to another Database
	public function selectDB($dbName){
		$this->sql = NULL;
		$this->dbName = $dbName;			
		
		if ($this->type == 'Mysql'){
			try {
				$this->sql = new PDO("mysql:dbname=$dbName;host=$this->host", $this->user, $this->pass);
			} catch(PDOException $e) {
			  	die($e->getMessage());
			}			
		} else if ($this->type == 'Sqlite') {
			try {
				$this->sql = new PDO("sqlite:".$dbName);  
			} catch (PDOException $e) {
				die($e->getMessage());
			}			
		}	
	}
	
	//Return the result of an Select statement
	public function fObject($mode = "single") {
		if ($mode == "single") {
	  		return array_shift($this->result);
		} else if ($mode == "all") {
			return $this->result;
		}
	}

	//Give the affected rows back from an select statement
	public function numrows() {
		return count($this->result);
	}
	
	//Start of an transaction	
	public function beginTransaction() {
		$this->sql->beginTransaction();
	}
	
	//Commit an transaction	
	public function Commit() {
		$this->sql->Commit();
	}

	//Return the last inserted ID
	public function lastInsertId() {
		return $this->sql->lastInsertId();	
	}

	//Gives a list with all tablenames back
	public function getTables() {
		if ($this->type == "Sqlite") {
			$tables = array();
			$this->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'");
			if ($this->numrows() == 0) {
				return $tables;
			}
			while ($row = $this->fObject()) {
				$tables[$row->tbl_name] = true;
			}
			
			return $tables;
		}
	}

	//Gives infos back about the tables	
	public function getTableInfos($tbl_name) {
		if ($this->type == "Sqlite") {
		 	$table_info = array();
			$this->query("SELECT tbl_name, sql FROM sqlite_master WHERE `type` = 'table' AND `tbl_name` = '$tbl_name'");
			if ($this->numrows() == 0) {
				return $table_info;
			}
			
			$tbl_sql = $this->fObject();
			$table_info["sql"] = $tbl_sql->sql;
			
		 	$tmp = $this->sql->query("SELECT * FROM $tbl_name LIMIT 0, 1");
			for ($i = 0; $i < $tmp->columnCount(); $i++) {
				$temp = $tmp->getColumnMeta($i);
				$table_info["columns"]["name"] = $temp["name"];
				$table_info["columns"]["type"] = $temp["native_type"];
				$table_info["columns"]["flags"] = $temp["flags"];				
			}
			return $table_info;
		}
	}
	
	public function getLastQuery() {
		return $this->lastQuery;
	}
	
/*===============================
** Name: loadSQLFile
** Loads an sql file if there is an update
** Will load the sql file with name $namexx.xx.xx.xx.sql if xx.xx.xx.xx is greater
** than settings[$name . "_sql_version"]
*/	public static function loadSQLFile($module, $name, $forceUpdate = false) {
		global $db;
		$name = strtolower($name);
		
		// only letters, numbers, underscores are allowed
		if (!preg_match('/^[a-z0-9_]+$/', $name)) {
			Logger::log(__FILE__, "Invalid SQL file name: '$name' for module: '$module'.  Only numbers, letters, and underscores permitted.", ERROR);
			return;
		}
		
		$settingName = $name . "_db_version";
		
		$core_dir = "./core/$module";
		$modules_dir = "./modules/$module";
		$dir = '';
		if ($d = dir($modules_dir)) {
			$dir = $modules_dir;
		} else if ($d = dir($core_dir)) {
			$dir = $core_dir;
		}
		
		$currentVersion = Settings::get($settingName);
		if ($currentVersion === false) {
			$currentVersion = 0;
		}

		$file = false;
		$maxFileVersion = 0;  // 0 indicates no version
		if ($d) {
			while (false !== ($entry = $d->read())) {
				if (is_file("$dir/$entry") && preg_match("/^" . $name . "([0-9.]*)\\.sql$/i", $entry, $arr)) {
					// if there is no version on the file, set the version to 0, and force update every time
					if ($arr[1] == '') {
						$file = $entry;
						$maxFileVersion = 0;
						$forceUpdate = true;
						break;
					}

					if (Util::compare_version_numbers($arr[1], $maxFileVersion) >= 0) {
						$maxFileVersion = $arr[1];
						$file = $entry;
					}
				}
			}
		}
		
		if ($file === false) {
			Logger::log(__FILE__, "No SQL file found with name '$name'", ERROR);
		} else if ($forceUpdate || Util::compare_version_numbers($maxFileVersion, $currentVersion) > 0) {
			$fileArray = file("$dir/$file");
			//$db->beginTransaction();
			forEach ($fileArray as $num => $line) {
				$line = trim($line);
				// don't process comment lines or blank lines
				if ($line != '' && substr($line, 0, 1) != "#") {
					$db->exec($line);
				}
			}
			//$db->Commit();
			
			// if the file had a version, tell them the start and end version
			// otherwise, just tell them we're updating the database
			if ($maxFileVersion != 0) {
				Logger:log(__FILE__, "Updating '$name' database from '$currentVersion' to '$maxFileVersion'...Finished!", INFO);
			} else {
				Logger:log(__FILE__, "Updating '$name' database...Finished!", INFO);
			}

		
			if (!Settings::save($settingName, $maxFileVersion)) {
				Settings::add($settingName, $module, 'noedit', $maxFileVersion);
			}
		} else {
			Logger:log(__FILE__, "Updating '$name' database...already up to date! version: '$currentVersion'", INFO);
		}
	}
}
?>
