<?php declare(strict_types=1);

use App\Controllers\GraphQL;
use Dotenv\Dotenv;

require_once __DIR__ . "/vendor/autoload.php";

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 204 No Content");
    exit(0);
}

$dotenv = Dotenv::createImmutable(__DIR__ . "/");
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.1 404 Not Found");
        echo json_encode(['message' => 'Not Found']);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(['message' => 'Method Not Allowed', 'allowedMethods' => $allowedMethods]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo call_user_func_array($handler, [$vars]);
        break;
}
