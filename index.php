<?php
spl_autoload_extensions(".php");
spl_autoload_register(function($class) {
    $filePath = __DIR__ . "/" . str_replace("\\", "/", $class) . ".php";
    require_once($filePath);
});

$routes = include('Routing/routes.php');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if (isset($routes[$path])) {
    $renderer = $routes[$path]();

    try {
        foreach ($renderer->getFields() as $name => $value) {

            $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("Access-Control-Allow-Origin: *");
                header("{$name}: {$sanitized_value}");
            } else {
                http_response_code(500);
            }
            print($renderer->getContent());
        }
    }
    catch (Exception $e) {
        http_response_code(500);
        print('error: ' . $e);
        print("Internal error, please contact the admin.<br>");

    }
} else {
    http_response_code(404);
    echo "404 Not Found: The requested route was not found on this server.";
}