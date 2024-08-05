<?php declare(strict_types=1);

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;

class OrderStatusRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
    }

    public function insert(string $status) {
        $statusId =  $this->getStatusId();

        $this->createQueryBuilder()
            ->insert('order_status')
            ->values([
                'status_id' => ':status_id',
                'status' => ':status',
            ])
            ->setParameters([
                'status_id' => $statusId,
                'status' => $status,
            ])
            ->executeStatement();
    }

    public function getStatusId(): int
    {
        $count = $this->createQueryBuilder()
            ->select('COUNT(*) as count')
            ->from('order_status')
            ->executeQuery()
            ->fetchOne();

        if ($count === 0) {
            return 1;
        }

        return $count + 1;
    }
}