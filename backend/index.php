<?php
// Simple landing redirect to API history to avoid 403 at root
header('Location: /api/history');
http_response_code(302);
exit;


