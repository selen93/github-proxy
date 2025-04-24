<?php


header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = [
        'status' => 'success',
        'message' => 'This will soon be a github search proxy :)',
    ];
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET requests are allowed']);
}
