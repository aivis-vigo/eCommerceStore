<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Brand\Order\ShopOrderRepository;

class ShopOrderService {
    protected $shopOrderRepository;

    public function __construct() {
        $this->shopOrderRepository = new ShopOrderRepository();
    }

    public function insert(array $order): void {
        $this->getRepository()->insert($order);
    }

    public function getRepository(): ShopOrderRepository {
        return $this->shopOrderRepository;
    }
}