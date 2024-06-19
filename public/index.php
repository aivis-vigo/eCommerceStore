<?php declare(strict_types=1);

//use \GuzzleHttp\Client as GuzzleClient;

require_once "../vendor/autoload.php";

use App\Models\Categories\Section;
use App\Models\Factories\FashionProductFactory;
use App\Models\Factories\TechProductFactory;

$path = __DIR__ . "/../data/data.json";
$data = file_get_contents($path);
$decoded = json_decode($data);

$sections = [];
$categories = $decoded->data->categories;

foreach ($categories as $category) {
    $section = new Section($category->name);

    $sections[] = $section;
}


$devices = [];
$clothing = [];

$tech = $decoded->data->products;
$techCreator = new TechProductFactory();
$fashionCreator = new FashionProductFactory();

foreach ($tech as $product) {
    if ($product->category == "tech") {
        $device = $techCreator->createProduct($product);

        $devices[] = $device;
    } else {
        $apparel = $fashionCreator->createProduct($product);

        $clothing[] = $apparel;
    }
}

echo "<pre>";
var_dump($clothing);
var_dump($devices);
echo "<pre>";