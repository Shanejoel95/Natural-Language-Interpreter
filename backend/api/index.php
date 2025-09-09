<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/claude-api.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_SERVER['PATH_INFO'] ?? '/';

    if ($method === 'POST' && $path === '/interpret') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['query'])) {
            throw new Exception('Query is required');
        }
        $userQuery = $input['query'];
        $requestType = $input['type'] ?? 'date';

        // Use ChatGPTAPI (OpenAI) implementation in claude-api.php
        $llm = new ChatGPTAPI();
        if ($requestType === 'date') {
            $response = $llm->interpretDate($userQuery);
        } else {
            $response = $llm->interpretText($userQuery, $requestType);
        }

        $db = Database::getInstance();
        $db->saveRequest($userQuery, $requestType, $response);

        echo json_encode(['success' => true, 'data' => $response]);
    } elseif ($method === 'GET' && $path === '/history') {
        $db = Database::getInstance();
        $history = $db->getHistory();
        echo json_encode(['success' => true, 'data' => $history]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}


