<?php declare(strict_types=1);

//use \GuzzleHttp\Client as GuzzleClient;

require_once "../vendor/autoload.php";

use App\Factories\FactoryManager;
use App\Models\Categories\Section;

$path = __DIR__ . "/../data/data.json";
$data = file_get_contents($path);
$decoded = json_decode($data);

$sections = [];
$categories = $decoded->data->categories;

foreach ($categories as $category) {
    $section = new Section($category->name);

    $sections[] = $section;
}

$allProducts = $decoded->data->products;


$devices = [];
$clothing = [];

$factoryManager = new FactoryManager();

foreach ($allProducts as $product) {
    $factory = $factoryManager->getFactory($product->category);
    $item = $factory->createProduct($product);

    if ($product->category == "tech") {
        $devices[] = $item;
    } else {
        $clothing[] = $item;
    }
}

/*echo "<pre>";
var_dump($clothing);
var_dump($devices);
echo "<pre>";*/

$techController = new \App\Controllers\TechController();
echo $techController->index();