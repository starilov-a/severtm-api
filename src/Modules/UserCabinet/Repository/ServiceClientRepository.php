<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\ParameterType;

final class ServiceClientRepository extends ServiceEntityRepository
{
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

        $conn = $this->getEntityManager()->getConnection();
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
