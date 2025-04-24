<?php


header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $name = $_GET['q'] ?? '';

    if (!$name) {
        echo json_encode(['error' => 'Search query is missing']);
        exit;
    }

    $token = getenv('GITHUB_TOKEN');

    $url = 'https://api.github.com/search/users?q=' . urlencode($name);

    $curlHandle =  curl_init($url);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
        'Accept: application/vnd.github+json',
        'Authorization: Bearer ' . $token,
        'X-GitHub-Api-Version: 2022-11-28',
        'User-Agent: PHP-Proxy'
    ]);

    $response = curl_exec($curlHandle);
    $error = curl_error($curlHandle);
    curl_close($curlHandle);

    if ($error) {
        echo json_encode(['error' => $error]);
    }
    else {
        echo json_encode($response);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET requests are allowed']);
}
