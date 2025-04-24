<?php


header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $name = $_GET['q'] ?? '';
    $page = $_GET['page'] ?? 1;

    if (!$name) {
        echo json_encode(['error' => 'Search query is missing']);
        exit;
    }

    $token = getenv('GITHUB_TOKEN');

    $url = 'https://api.github.com/search/users?q=' . urlencode($name).'&per_page=100&page='.intval($page);

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
        $data = json_decode($response, true);

        $usernames = array_map(fn($user) => $user['login'], $data['items']);

        echo json_encode([
            'query' => "Searched for ".$name,
            'usernames' => $usernames
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET requests are allowed']);
}
