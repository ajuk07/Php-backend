<?php

namespace App\Config;

use PDO;


class Database
{
	private $host = 'localhost';
	private $db_name = 'ASIC_DB';
	private $username = 'postgres';
	private $password = 'postgres';
	public $conn;

	public function __construct()
	{
		$this->conn = null;
		try {
			$this->conn = new PDO("pgsql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}

	public function execute($query, $params = [])
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute($params);
		return $stmt;
	}

	public function fetchData($query, $params = [])
	{
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($params);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			return false;
		}
	}

	public function fetchSingle($query)
	{
		$stmt = $this->conn->query($query);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
}
