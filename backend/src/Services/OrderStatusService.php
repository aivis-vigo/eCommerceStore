<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Brand\Order\OrderStatusRepository;

class OrderStatusService {
    protected OrderStatusRepository $orderStatusRepository;

    public function __construct() {
        $this->orderStatusRepository = new OrderStatusRepository();
    }

    public function insert(string $status): void {
        $this->getRepository()->insert($status);
    }

    public function getRepository(): OrderStatusRepository {
        return $this->orderStatusRepository;
    }
}