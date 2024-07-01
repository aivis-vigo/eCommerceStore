<?php declare(strict_types=1);

namespace App\Database\Seeds;

use App\Database\Database;
use App\Models\Categories\Section;
use App\Models\DataSource;
use App\Database\Factories\FactoryManager;
use App\Repository\Attribute\AttributeOptionRepository;
use App\Repository\Attribute\AttributeTypeRepository;
use App\Repository\Attribute\ProductAttributeRepository;
use App\Repository\Brand\BrandRepository;
use App\Repository\Category\CategoryRepository;
use App\Repository\Category\SizeCategoryRepository;
use App\Repository\Color\ColorRepository;
use App\Repository\Image\ImageRepository;
use App\Repository\Option\SizeOptionRepository;
use App\Repository\Product\ProductItemRepository;
use App\Repository\Product\ProductRepository;
use App\Repository\Product\ProductVariationRepository;

class DatabaseSeeder
{
    private array $sections, $devices, $clothing;
    private DataSource $data;
    private FactoryManager $factoryManager;
    private Database $database;
    private $category,
        $sizeCategory,
        $sizeOption,
        $brand,
        $product,
        $productItem,
        $image,
        $productVariation,
        $color,
        $attributeType,
        $attributeOption,
        $productAttribute;

    public function __construct()
    {
        $this->sections = [];
        $this->devices = [];
        $this->clothing = [];
        $this->data = new DataSource(__DIR__ . "/../../../data/data.json");
        $this->factoryManager = new FactoryManager();
        $this->database = new Database();

        $this->category = new CategoryRepository($this->getDatabase());
        $this->sizeCategory = new SizeCategoryRepository($this->getDatabase());
        $this->sizeOption = new SizeOptionRepository($this->getDatabase());
        $this->brand = new BrandRepository($this->getDatabase());
        $this->product = new ProductRepository($this->getDatabase());
        $this->productItem = new ProductItemRepository($this->getDatabase());
        $this->image = new ImageRepository($this->getDatabase());
        $this->productVariation = new ProductVariationRepository($this->getDatabase());
        $this->color = new ColorRepository($this->getDatabase());
        $this->attributeType = new AttributeTypeRepository($this->getDatabase());
        $this->attributeOption = new AttributeOptionRepository($this->getDatabase());
        $this->productAttribute = new ProductAttributeRepository($this->getDatabase());

        $this->structureData();
    }

    public function run(): void
    {
        var_dump($this->devices);

        /*foreach ($this->sections as $section) {
            $category->insertOne($section->getName());
        }*/

        // todo: need to insert only once for each category and if it doesn't already exist
        // todo: fashion and tech has its own seeders or each model
        /*foreach ($clothing as $wearable) {
            $sizes = $wearable->getSize()->getOptions();

            if (count($sizes) > 0) {
                foreach ($sizes as $size) {
                    if (is_numeric($size->getValue())) {
                        $sizeOption->insertOne(1, $size->getDisplayValue(), $size->getvalue());
                    } else {
                        $sizeOption->insertOne(2, $size->getDisplayValue(), $size->getvalue());
                    }
                }
            }

            $brandName = $wearable->getBrand();
            $brand->insertOne($brandName);

            $product->insertOne($wearable);

            $productItem->insertOne($wearable);

            foreach ($wearable->getPictures() as $picture) {
                $image->insertOne($wearable->getId(), $picture);
            }

            $productVariation->insertOne($wearable);
        }*/

        /*foreach ($devices as $device) {
            $brand->insertOne($device->getBrand());
            $product->insertOne($device);
            if (method_exists($device, "getColor")) {
                foreach ($device->getColor()->getOptions() as $deviceColor) {
                    $color->insertOne($deviceColor);
                }
            }
            $productItem->insertOne($device);
            // todo: only one item gets pictures
            foreach ($device->getPictures() as $picture) {
                $image->insertOne($device->getId(), $picture);
            }
            $productVariation->insertOne($device);
            $attributeType->insertOne($device);
            $attributeOption->insertOne($device->getAttributeArray());
            $productAttribute->insertOne($device);
        }*/
    }

    public function structureData(): void
    {
        $categories = $this->data->getCategories();
        $allProducts = $this->data->getProducts();

        foreach ($categories as $category) {
            $section = new Section($category->name);

            $this->sections[] = $section;
        }

        foreach ($allProducts as $product) {
            $factory = $this->factoryManager->getFactory($product->category);
            $item = $factory->createProduct($product);

            if ($item->getCategory() == "tech") {
                $this->devices[] = $item;
            } else {
                $this->clothing[] = $item;
            }
        }
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
}