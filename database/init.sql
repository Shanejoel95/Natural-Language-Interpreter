-- Database initialization for Natural Language Date Interpreter
CREATE DATABASE IF NOT EXISTS date_interpreter;
USE date_interpreter;

CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_query TEXT NOT NULL,
    request_type VARCHAR(50) DEFAULT 'date',
    json_response JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_request_type (request_type)
);



