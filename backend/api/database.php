<?php
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
            );
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function saveRequest($userQuery, $requestType, $jsonResponse) {
        $sql = 'INSERT INTO requests (user_query, request_type, json_response) VALUES (:user_query, :request_type, :json_response)';
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':user_query' => $userQuery,
            ':request_type' => $requestType,
            ':json_response' => json_encode($jsonResponse, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function getHistory($limit = 20) {
        $sql = 'SELECT * FROM requests ORDER BY created_at DESC LIMIT :limit';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as &$row) {
            $row['json_response'] = json_decode($row['json_response'], true);
        }
        return $results;
    }
}


