<?php

$routes = [
    [
        'path' => 'auth',
        'file' => 'Auth.php'
    ],
    [
        'path' => 'activity',
        'file' => 'Activity.php'
    ],
    [
        'path' => 'users',
        'file' => 'Users.php'
    ],
];

$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/v1';

if (strpos($requestUri, $basePath) === 0) {
    $parts = explode('/', $requestUri);

    $basePathIndex = array_search(trim($basePath, '/'), $parts);
    if ($basePathIndex !== false && isset($parts[$basePathIndex + 1])) {
        $routePath = $parts[$basePathIndex + 1];

        foreach ($routes as $route) {
            if ($routePath == $route['path']) {
                $routeFilePath = APP_ROOT . '/app/Routes/' . $route['file'];
                if (file_exists($routeFilePath)) {
                    require_once $routeFilePath;
                    exit;
                } else {
                    http_response_code(404);
                    echo '404 Not Found';
                    exit;
                }
            }
        }

        http_response_code(404);
        echo '404 Not Found';
        exit;
    }
}
http_response_code(404);
echo '404 Not Found';
exit;
