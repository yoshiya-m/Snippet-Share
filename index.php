<?php

use Helpers\DatabaseHelper;

spl_autoload_extensions(".php");
spl_autoload_register(function ($class) {
    $filePath = __DIR__ . "/" . str_replace("\\", "/", $class) . ".php";
    if (file_exists($filePath)) {
        require_once($filePath);
    } 
});
$DEBUG = false;
$routes = include('Routing/routes.php');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if ($path === 'js/monaco-editor-setup.js') {
    header('Content-Type: application/javascript');
    echo file_get_contents(__DIR__ . '/js/monaco-editor-setup.js');
    exit;
}


if (isset($routes[$path]) || DatabaseHelper::doesPathExist($path)) {
    
    

    if (isset($routes[$path])) {
        $renderer = $routes[$path]('');
    } else if (DatabaseHelper::isExpired($path)) {
        http_response_code(404);
        echo "Requested URL is expired.";
        exit;
    } else {
        $renderer = $routes['']($path);

    }
    
    try {

        foreach ($renderer->getFields() as $name => $value) {

            $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                http_response_code(500);
                print("Internal error, please contact the admin.<br>");
                exit;
            }
            http_response_code(200);
            print($renderer->getContent());
        }
    } catch (Exception $e) {
        http_response_code(500);
        if ($DEBUG) {
            print('error: ' . $e);
        }

        print("Internal error, please contact the admin.<br>");
    }
} else {


    http_response_code(404);
    echo "404 Not Found: The requested route was not found on this server.";

    echo $path;
}
