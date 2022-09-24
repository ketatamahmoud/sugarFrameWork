<?php
namespace Core;

use PDO;
use PDOException;

class Database{

	private static $pdo;

	public function __construct($config){
		if(is_null(self::$pdo)){
			try{
				self::$pdo = new PDO($config['connection'].';dbname='.$config['name'], $config['username'], $config['password'],$config['options']);
			}
			catch(PDOException $except){
				echo"Connection failed: ". $except->getMessage();
				die();
			}
		}
	}

	public function getPDO(){
		return self::$pdo;
	}

	public function execute_query($sql, $params = null){
		if ($params == null) {
            $result = self::$pdo->query($sql);
        }
        else {
            $result = self::$pdo->prepare($sql);
            $result->execute($params);
        }
		if(!$result) {
			$error=self::$pdo->errorInfo();
			echo "Unable to read, code ", self::$pdo->errorCode(),$error[2];
			die();
		}else {
			return $result;
		}
	}
}
