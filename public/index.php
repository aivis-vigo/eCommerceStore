<?php declare(strict_types=1);

//use \GuzzleHttp\Client as GuzzleClient;

require_once "../vendor/autoload.php";

use App\Database\Database;
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
var_dump($devices[2]);
var_dump($clothing);
echo "<pre>";*/
//die();

//die(var_dump($sections));

$database = new Database();

$category = new \App\Repository\Category\CategoryRepository($database);
$sizeCategory = new \App\Repository\Category\SizeCategoryRepository($database);
$sizeOption = new \App\Repository\Option\SizeOptionRepository($database);
$brand = new \App\Repository\Brand\BrandRepository($database);
$product = new \App\Repository\Product\ProductRepository($database);
$productItem = new \App\Repository\Product\ProductItemRepository($database);
$image = new \App\Repository\Image\ImageRepository($database);
$productVariation = new \App\Repository\Product\ProductVariationRepository($database);
$color = new \App\Repository\Color\ColorRepository($database);
$attributeType = new \App\Repository\Attribute\AttributeTypeRepository($database);
$attributeOption = new \App\Repository\Attribute\AttributeOptionRepository($database);
$productAttribute = new \App\Repository\Attribute\ProductAttributeRepository($database);

/*foreach ($sections as $section) {
    $category->insertOne($section->getName());
}*/

// todo: need to insert only once for each category and if it doesn't already exist
// todo: fashion and tech has its own seeders or each model
foreach ($clothing as $wearable) {
    /*$sizes = $wearable->getSize()->getOptions();

    if (count($sizes) > 0) {
        foreach ($sizes as $size) {
            if (is_numeric($size->getValue())) {
                $sizeOption->insertOne(1, $size->getDisplayValue(), $size->getvalue());
            } else {
                $sizeOption->insertOne(2, $size->getDisplayValue(), $size->getvalue());
            }
        }
    }*/

    /*$brandName = $wearable->getBrand();
    $brand->insertOne($brandName);*/

    // $product->insertOne($wearable);

    //$productItem->insertOne($wearable);

    /*foreach ($wearable->getPictures() as $picture) {
        $image->insertOne($wearable->getId(), $picture);
    }*/

    //$productVariation->insertOne($wearable);
}

foreach ($devices as $device) {
    //$brand->insertOne($device->getBrand());
    //$product->insertOne($device);
    /*if (method_exists($device, "getColor")) {
        foreach ($device->getColor()->getOptions() as $deviceColor) {
            $color->insertOne($deviceColor);
        }
    }*/
    //$productItem->insertOne($device);
    // todo: only one item gets pictures
    /*foreach ($device->getPictures() as $picture) {
        $image->insertOne($device->getId(), $picture);
    }*/
    //$productVariation->insertOne($device);
    //$attributeType->insertOne($device);
    //$attributeOption->insertOne($device->getAttributeArray());
    //$productAttribute->insertOne($device);
}
