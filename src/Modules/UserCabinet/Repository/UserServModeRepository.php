<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\UserServMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserServModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserServMode::class);
    }

    /**
     * Проверка наличия активного режима у пользователя в конкретном финпериоде,
     * отфильтрованного по коду услуги (JOIN через режим → услуга).
     */
    public function hasActiveForUserAndServiceCode(int $uid, int $fid, string $serviceCode): bool
    {
        $qb = $this->createQueryBuilder('usm')
            ->select('1')
            ->join('usm.mode', 'm')
            ->join('m.service', 's')
            ->andWhere('usm.user = :uid')->setParameter('uid', $uid)
            ->andWhere('usm.finPeriod = :fid')->setParameter('fid', $fid)
            ->andWhere('usm.isActive = 1')
            ->andWhere('s.strCode = :code')->setParameter('code', $serviceCode)
            ->setMaxResults(1);

        return (bool)$qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Удалить назначенные режимы по пользователю/финпериоду/коду услуги
     * (аналог вашего SQL, только через DQL).
     */
    public function clearForUserAndServiceCode(int $uid, int $fid, string $serviceCode): int
    {
        // DQL bulk delete не умеет join'ы, поэтому проще через DBAL raw SQL.
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<SQL
            DELETE um
            FROM user_serv_modes um
            JOIN prod_serv_modes m ON m.id = um.srvmode_id
            JOIN products_services s ON s.id = m.srv_id
            WHERE um.uid = :uid AND um.fid = :fid AND s.str_code = :code
        SQL;

        return $conn->executeStatement($sql, [
            'uid' => $uid,
            'fid' => $fid,
            'code' => $serviceCode,
        ]);
    }
}
