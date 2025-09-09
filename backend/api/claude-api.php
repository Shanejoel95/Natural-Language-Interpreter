<?php
require_once __DIR__ . '/config.php';

class ChatGPTAPI {
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';
    private $model = 'gpt-4o-mini';

    public function __construct() {
        $this->apiKey = OPENAI_API_KEY;
        if (empty($this->apiKey)) {
            throw new Exception('OpenAI API key not configured');
        }
    }

    public function interpretDate($userQuery) {
        $prompt = "Convert the following natural language date expression to an actual date.\n" .
                  "Today's date is " . date('Y-m-d') . ".\n" .
                  "Expression: \"$userQuery\"\n\n" .
                  "Respond ONLY with a JSON object in this exact format:\n" .
                  "{\n  \"date\": \"YYYY-MM-DD\",\n  \"request\": \"original request\"\n}\n\n" .
                  "Do not include any other text, markdown, or explanation. Only output valid JSON.";
        return $this->sendRequest($prompt);
    }

    public function interpretText($userQuery, $format) {
        $prompts = [
            'product' => "Create a product description based on the following request.\n" .
                         "Request: \"$userQuery\"\n\n" .
                         "Respond ONLY with a valid JSON object containing these exact fields:\n" .
                         "{\n" .
                         "  \"product_name\": \"string\",\n" .
                         "  \"key_features\": [\"feature1\", \"feature2\", \"feature3\"],\n" .
                         "  \"technical_specs\": {\"spec1\": \"value1\", \"spec2\": \"value2\"},\n" .
                         "  \"original_request\": \"$userQuery\"\n" .
                         "}\n\n" .
                         "Output only valid JSON, no markdown or other text.",
            
            'summary' => "Summarize the following text.\n" .
                         "Text: \"$userQuery\"\n\n" .
                         "Respond ONLY with a valid JSON object containing these exact fields:\n" .
                         "{\n" .
                         "  \"summary\": \"brief summary here\",\n" .
                         "  \"key_points\": [\"point1\", \"point2\", \"point3\"],\n" .
                         "  \"original_request\": \"$userQuery\"\n" .
                         "}\n\n" .
                         "Output only valid JSON, no markdown or other text."
        ];
        
        $prompt = $prompts[$format] ?? $prompts['summary'];
        return $this->sendRequest($prompt);
    }

    private function sendRequest($prompt) {
        // Prepare the request data for OpenAI
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant that responds only with valid JSON. Never include markdown code blocks or any text outside the JSON structure.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3, // Lower temperature for more consistent JSON output
            'max_tokens' => 1000,
            'response_format' => ['type' => 'json_object'] // Forces JSON response (GPT-4 Turbo only)
        ];

        // If using older model, remove response_format
        if ($this->model === 'gpt-3.5-turbo') {
            unset($data['response_format']);
        }

        // Initialize cURL
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Handle cURL errors
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }

        // Handle HTTP errors
        if ($httpCode !== 200) {
            $errorMsg = "API request failed with code $httpCode";
            if ($response) {
                $errorData = json_decode($response, true);
                if (isset($errorData['error']['message'])) {
                    $errorMsg .= ": " . $errorData['error']['message'];
                }
            }
            throw new Exception($errorMsg);
        }

        // Parse the OpenAI response
        $result = json_decode($response, true);
        
        if (!isset($result['choices'][0]['message']['content'])) {
            throw new Exception('Unexpected API response format');
        }

        $content = $result['choices'][0]['message']['content'];
        
        // Clean the response (remove markdown if present)
        $content = trim($content);
        $content = preg_replace('/^```json\s*/i', '', $content);
        $content = preg_replace('/\s*```$/i', '', $content);
        $content = trim($content);
        
        // Parse the JSON response
        $jsonResponse = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Failed to parse JSON: " . $content);
            throw new Exception('Invalid JSON response from API: ' . json_last_error_msg());
        }

        return $jsonResponse;
    }

    public function testConnection() {
        try {
            $testPrompt = 'Respond with only this JSON: {"status": "connected", "model": "' . $this->model . '"}';
            $result = $this->sendRequest($testPrompt);
            return $result;
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}