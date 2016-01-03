<?php

require 'vendor/autoload.php';


	$app = new \Slim\Slim();

	$app->get('/:domain/:entity', 'getDatas');
	$app->get('/:domain/:entity/:id',  'getData');
	$app->get('/:domain/:entity/search/:attribute/:value', 'searchData');
	$app->post('/:domain/:entity', 'addData');
	$app->put('/:domain/:entity/:id', 'updateData');
	$app->delete('/:domain/:entity/:id',   'deleteData');
 
	$app->run();

	
	function getDatas($domain,$entity) {
		$sql = "select * FROM $entity";
		try {
			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"data": ' . json_encode($result) . '}';
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
 
	function getData($domain,$entity,$id) {
		$sql = "SELECT * FROM $entity WHERE id=:id";
		try {
			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			$stmt->bindParam("id", $id);
			$stmt->execute();
			$result = $stmt->fetchObject();
			$db = null;
			echo '{"data": ' . json_encode($result) . '}';
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
 
	function addData($domain,$entity) {
		try {
			$request = \Slim\Slim::getInstance()->request();
			$data = json_decode($request->getBody(), true);
			if(is_null($data)){
				$attributes = array();
				$values = array();
				$data = array();
			}else{
				$attributes = array_keys($data);
				$values = array_values($data);
			}
			
			$sql = "INSERT INTO $entity (";
			
			for ($i = 1; $i <= count($attributes); $i++) {
				$attribute = $attributes[$i];
				$sql = $sql . $attribute ;
				if($i < count($attributes)){
					$sql = $sql . "," ;
				}
			}
			$sql = $sql .") VALUES (";
			for ($i = 1; $i <= count($values); $i++) {
				$value = $values[$i];
				$sql = $sql . $value ;
				if($i < count($values)){
					$sql = $sql . "," ;
				}
			}
			$sql = $sql .")";
		
			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$data['id'] = $db->lastInsertId();
			$db = null;
			echo '{"data": ' . json_encode($data) . '}';
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
 
	function updateData($domain,$entity,$id) {
		try {  
			$request = \Slim\Slim::getInstance()->request();
			$body = $request->getBody();
			$data = json_decode($body,true);
			$sql = "UPDATE $entity SET ";
			if(is_null($data)){
				throw new Exception('No data to change.');
			}else{
				$attributes = array_keys($data);
				$values = array_values($data);
			}
			

			for ($i = 0; $i < count($attributes); $i++) {
				$attribute = $attributes[$i];
				$sql = $sql . $attribute ;
				$sql = $sql . "=:update".$attribute ." ";
				if($i < count($attributes)-1){
					$sql = $sql . "," ;
				}
			}
			
			
			$sql = $sql . "WHERE id=:id";

			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			
			for ($i = 0; $i < count($attributes); $i++) {
				$attribute = $attributes[$i];
				$stmt->bindParam("update".$attribute, $values[$i]);
			}

			$stmt->bindParam("id", $id);
			$stmt->execute();
			$db = null;
			echo '{"data": ' . json_encode($data) . '}';
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
 
	function deleteData($domain,$entity,$id) {
		$sql = "DELETE FROM $entity WHERE id=:id";
		try {
			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			$stmt->bindParam("id", $id);
			$stmt->execute();
			$db = null;
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	 
	function searchData($domain,$entity,$attribute,$value) {
		$sql = "SELECT * FROM $entity WHERE $attribute LIKE :value ORDER BY $attribute";
		try {
			$db = getConnection($domain);
			$stmt = $db->prepare($sql);
			$stmt->bindParam("value", $value);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"data": ' . json_encode($result) . '}';
		} catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	 
	function getConnection($dbname) {
		$dbhost="127.0.0.1";
		$dbuser="root";
		$dbpass="";
		$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}
	
?>
