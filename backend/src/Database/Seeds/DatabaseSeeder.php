<?php declare(strict_types=1);

namespace App\Database\Seeds;

use App\Models\Categories\Section;
use App\Models\DataSource;
use App\Database\Factories\FactoryManager;
use App\Services\AttributeOptionService;
use App\Services\AttributeTypeService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\ProductAttributeService;
use App\Services\ProductService;
use App\Services\ProductVariationService;
use App\Services\SizeCategoryService;
use App\Services\SizeOptionService;

// data are structure when class instance is created
class DatabaseSeeder
{
    private array $sections, $devices, $clothing;
    private DataSource $data;
    private FactoryManager $factoryManager;
    private $category,
        $sizeCategory,
        $sizeOption,
        $brand,
        $product,
        $image,
        $productVariation,
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

        $this->category = new CategoryService();
        $this->sizeCategory = new SizeCategoryService();
        $this->sizeOption = new SizeOptionService();
        $this->brand = new BrandService();
        $this->product = new ProductService();
        $this->image = new ImageService();
        $this->productVariation = new ProductVariationService();
        $this->attributeType = new AttributeTypeService();
        $this->attributeOption = new AttributeOptionService();
        $this->productAttribute = new ProductAttributeService();

        $this->structureData();
    }

    public function run(): void
    {

        $this->insertCategories($this->sections);
        $this->insertFashionItems($this->clothing);
        $this->insertElectronics($this->devices);
    }

    public function insertCategories(array $categories): void
    {
        foreach ($categories as $section) {
            $this->category->insertOne($section->getName());
        }
    }

    public function insertFashionItems(array $clothing): void
    {
        foreach ($clothing as $wearable) {
            $sizes = $wearable->getSize()->getOptions();
            if (count($sizes) > 0) {
                foreach ($sizes as $size) {
                    if (is_numeric($size->getValue())) {
                        $this->sizeOption->insertOne([
                            'sizeCategoryId' => 1,
                            'displayValue' => $size->getDisplayValue(),
                            'value' => $size->getValue(),
                        ]);
                    } else {
                        $this->sizeOption->insertOne([
                            'sizeCategoryId' => 2,
                            'displayValue' => $size->getDisplayValue(),
                            'value' => $size->getValue(),
                        ]);
                    }
                }
            }
            $brandName = $wearable->getBrand();
            $this->brand->insertOne($brandName);
            $this->product->insertOne($wearable);
            foreach ($wearable->getImages() as $image) {
                $this->image->insertOne([
                    'productId' => $wearable->getId(),
                    'imageUrl' => $image,
                ]);
            }
            // only for clothing
            $this->productVariation->insertOne($wearable);
        }
    }

    public function insertElectronics(array $devices): void
    {
        foreach ($devices as $device) {
            $this->brand->insertOne($device->getBrand());
            $this->product->insertOne($device);
            foreach ($device->getImages() as $image) {
                $this->image->insertOne([
                    'productId' => $device->getId(),
                    'imageUrl' => $image,
                ]);
            }
            $this->attributeType->insertOne($device);
            $this->attributeOption->insertOne($device->getAttributeArray());
            $this->productAttribute->insertOne($device);
        }
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
}