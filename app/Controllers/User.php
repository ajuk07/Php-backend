<?php

namespace App\Controllers;

use App\Lib\Response;
use App\Config\Database;

class User
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function index()
	{
		echo 'User Controllers index function';
	}

	public function getAllUsers($page = 1, $limit = 20)
	{
		$offset = ($page - 1) * $limit;

		$query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
		$params = [':limit' => $limit, ':offset' => $offset];
		$result = $this->db->fetchData($query, $params);
		$response = new Response();
		return $response->toJSON($result);
	}

	public function getUserById($userId)
	{
		$uri = $_SERVER['REQUEST_URI'];

		$pattern = '#^/v1/users/(\d+)$#';
		if (preg_match($pattern, $uri, $matches)) {
			$userId = $matches[1];
			$query = "SELECT * FROM users WHERE id = :id";
			$params = [':id' => $userId];

			return $this->db->fetchSingle($query, $params);
		} else {
			return null;
		}
	}
	public function createUser()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if (!$data) {
			http_response_code(400);
			echo json_encode(['error' => 'Invalid or empty request body']);
			return;
		}

		$fields = implode(', ', array_keys($data));
		$placeholders = ':' . implode(', :', array_keys($data));

		$query = "INSERT INTO users ($fields) VALUES ($placeholders)";

		try {
			$this->db->execute($query, $data);
			$newUserId = $this->db->getLastInsertId();
			http_response_code(201);
			echo json_encode(['message' => 'User created successfully', 'user_id' => $newUserId]);
		} catch (PDOException $e) {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to create user: ' . $e->getMessage()]);
		}
	}

	public function updateUser($userId, $data)
	{
		$setStatements = [];
		$params = [':id' => $userId];

		foreach ($data as $key => $value) {
			$setStatements[] = "$key = :$key";
			$params[":$key"] = $value;
		}

		$setStatements = implode(', ', $setStatements);
		$query = "UPDATE users SET $setStatements WHERE id = :id";
		return $this->db->execute($query, $params);
	}

	public function deleteUser($userId)
	{
		$query = "DELETE FROM users WHERE id = :id";
		$params = [':id' => $userId];
		return $this->db->execute($query, $params);
	}
}
