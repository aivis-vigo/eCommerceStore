<?php declare(strict_types=1);

namespace App\Repositories\Brand\Order;

use App\Repositories\BaseRepository;

class ShopOrderRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
    }

    public function insert(array $order) {
        $orderStatusId = $this->getStatusId("Awaiting Payment");
        $orderDate = date("Y-m-d H:i:s");

        $this->createQueryBuilder()
            ->insert('shop_order')
            ->values([
                'order_id' => ':order_id',
                'order_status_id' => ':order_status_id',
                'order_total' => ':order_total',
                'order_date' => ':order_date',
            ])
            ->setParameters([
                'order_id' => $order['orderId'],
                'order_status_id' => $orderStatusId,
                'order_total' => $order['total'],
                'order_date' => $orderDate,
            ])
            ->executeStatement();
    }

    public function getStatusId(string $status): int {
        return $this->createQueryBuilder()
            ->select('status_id')
            ->from('order_status')
            ->where('status = :status')
            ->setParameter('status', $status)
            ->fetchOne();
    }
}