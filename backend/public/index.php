<?php declare(strict_types=1);

use App\Controllers\GraphQL;
use App\Database\Seeds\DatabaseSeeder;
use Dotenv\Dotenv;

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

/*$seeder = new DatabaseSeeder();
$seeder->run();
die();*/

/*echo "<pre>";
$productAttributes = (new \App\Services\ProductAttributeService())->findOneById('ps-5');
$ids = [];
foreach ($productAttributes as $productAttribute) {
    $ids[] = $productAttribute['attribute_option_id'];
}

$options = [];
foreach ($ids as $id) {
    $options[] = (new \App\Services\AttributeOptionService())->findOneById($id);
}

$attributes = [];
foreach ($options as $option) {
    $test = (new \App\Services\AttributeTypeService())->findOneById($option['attribute_type_id']);
    if (!in_array($test, $attributes)) {
        $attributes[] = $test;
    }
}
var_dump($attributes);
echo "</pre>";
die();*/

// todo: remove redundant graphql types
// todo: update graphql resolvers and method logic in repos

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