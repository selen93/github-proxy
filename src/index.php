<?php

// Set header as JSON, as required
header('Content-Type: application/json; charset=utf-8');

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET requests are allowed']);
}

// Get name and page from GET query
$name = $_GET['q'] ?? '';
$page = $_GET['page'] ?? 1;

// Basic Error Handling
if (!$name) {
    echo json_encode(['error' => 'Search query is missing']);
    exit;
}

// Get results from GitHub
$users = searchForGitHubUsers($name, $page);

// Forward the response to the user
if (isset($users['error'])) {
    echo json_encode($users);
} else {
    echo json_encode([
        'query' => "Searched for ". $name,
        'result' =>  $users
    ]);
}

function searchForGitHubUsers(string $name, int $page): array
{
    // set required paramters for GitHub API
    $token = getenv('GITHUB_TOKEN');
    $url = 'https://api.github.com/search/users?q=' . urlencode($name).'&per_page=100&page='.intval($page);

    // Start cURL handle and set paramters required by GitHub
    $curlHandle =  curl_init($url);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
        'Accept: application/vnd.github+json',
        'Authorization: Bearer ' . $token,
        'X-GitHub-Api-Version: 2022-11-28',
        'User-Agent: PHP-Proxy'
    ]);

    // Get response and error from cURL
    $response = curl_exec($curlHandle);
    $error = curl_error($curlHandle);
    curl_close($curlHandle);

    // return error if there is one
    if ($error) {
        return ['error' => $error];
    }

    // return usernames otherwise
    $data = json_decode($response, true);
    return array_map(fn($user) => $user['login'], $data['items']);
};
