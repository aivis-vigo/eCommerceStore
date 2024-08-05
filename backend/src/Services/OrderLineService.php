<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Order\OrderLineRepository;

class OrderLineService {
    protected $orderLineRepository;

    public function __construct() {
        $this->orderLineRepository = new OrderLineRepository();
    }

    public function insert(array $order): void {
        $this->getRepository()->insert($order);
    }

    public function getRepository(): OrderLineRepository {
        return $this->orderLineRepository;
    }
}