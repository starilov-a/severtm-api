<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ServiceClientRepository // extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        //parent::__construct($registry, Region::class);
        $this->entityManager = $entityManager;
    }

    /** Есть активная аренда в текущем финпериоде */
    public function hasRentNow(int $userId): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM user_serv_modes usm
            JOIN prod_serv_modes psm ON psm.id = usm.srvmode_id
            JOIN products_services ps ON ps.id  = psm.srv_id
            JOIN fin_periods fp       ON fp.id  = usm.fid
            WHERE usm.uid = :uid
              AND fp.is_current = 1
              AND usm.use_cost = 1
              AND usm.is_active = 1
              AND ps.str_code = :rent
            LIMIT 1
        SQL;

        $conn = $this->entityManager->getConnection();
        $val = $conn->fetchOne($sql, [
            'uid'       => $userId,
            'rent'      => 'rent',
        ], [
            'uid'       => ParameterType::INTEGER,
            'rent'      => ParameterType::STRING,
        ]);

        return $val !== false;
    }

}
