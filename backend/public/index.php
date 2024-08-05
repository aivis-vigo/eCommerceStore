<?php declare(strict_types=1);

use App\Controllers\GraphQL;
use Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . "/../vendor/autoload.php";

// todo: where to put this mess
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 204 No Content");
    exit(0);
}

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*echo "<pre>";
/* needed in order line also */
/* update will be needed after price is calculated
$orderId = Uuid::uuid4()->toString();

(new \App\Services\ShopOrderService())->insert($orderId);
$test = ['productId' => 'apple-airpods-pro', 'orderId' => $orderId, 'quantity' => 12, 'price' => 150];
(new \App\Services\OrderLineService())->insert($test);
echo "<pre>";
die();*/

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}