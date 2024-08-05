<?php declare(strict_types=1);

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;

class OrderLineRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
    }

    public function insert(array $order): void {
        $this->createQueryBuilder()
            ->insert('order_line')
            ->values([
                'product_id' => ':product_id',
                'order_id' => ':order_id',
                'quantity' => ':quantity',
                'price' => ':price',
            ])
            ->setParameters([
                /* todo: might change this back to just id */
                'product_id' => $order['productId'],
                'order_id' => $order['orderId'],
                'quantity' => $order['quantity'],
                'price' => $order['price'],
            ])
            ->executeStatement();
    }
}