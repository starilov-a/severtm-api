<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
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

    // Активные режимы пользователя (без интернета)
    public function findCurrentModesWithService(User $user): array
    {
        return $this->createQueryBuilder('usm')
            ->join('usm.mode', 'm')
            ->join('m.service', 's')
            ->join('usm.finPeriod', 'f')
            ->addSelect('m', 's')
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.isActive = 1')
            ->andWhere('f.isCurrent = 1')
            ->andWhere('s.strCode != :code')->setParameter('code', 'internet')
            ->orderBy('s.priority', 'ASC')
            ->addOrderBy('m.priority', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти активный UserServMode по ProdServMode и пользователю.
     * Использует текущий финпериод (isCurrent = 1).
     */
    public function findActiveByModeAndUser(ProdServMode $mode, User $user, ?FinPeriod $finPeriod = null): ?UserServMode
    {
        return $this->createQueryBuilder('usm')
            ->join('usm.finPeriod', 'f')
            ->andWhere('usm.mode = :mode')->setParameter('mode', $mode)
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.isActive = 1')
            ->andWhere('f.isCurrent = 1')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Есть ли активный user_ser_mode (режим) в указанном фин периоде у пользователя
    public function hasActiveMultiPeriodModes(User $user, FinPeriod $finPeriod): bool
    {
        $qb = $this->createQueryBuilder('usm')
            ->select('1')
            ->join('usm.mode', 'm')
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.finPeriod = :finPeriod')->setParameter('finPeriod', $finPeriod)
            ->andWhere('usm.isActive = 1')
            ->andWhere('m.periods > 1')
            ->setMaxResults(1);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * Поиск активных услуг пользователя
     * @return UserServMode[]
     */
    public function findCurrentActiveModes(User $user): array
    {
        return $this->createQueryBuilder('usm')
            ->join('usm.finPeriod', 'f')
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.isActive = 1')
            ->andWhere('f.isCurrent = 1')
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult();
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

    // Активные режимы пользователя (интернет)
    public function findActiveTariffsByUser(User $user): array
    {
        return $this->createQueryBuilder('usm')
            ->join('usm.mode', 'm')
            ->join('m.service', 's')
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.isActive = 1')
            ->andWhere('s.strCode = :code')->setParameter('code', 'internet')
            ->orderBy('s.priority', 'ASC')
            ->addOrderBy('m.priority', 'ASC')
            ->getQuery()->getResult();
    }

    // Активный режимм пользователя за период (интернет)
    public function findActiveTariffsByUserAndFinPeriod(User $user, FinPeriod $finPeriod): UserServMode
    {
        $userServMode = $this->createQueryBuilder('usm')
            ->join('usm.mode', 'm')
            ->join('m.service', 's')
            ->andWhere('usm.user = :user')->setParameter('user', $user)
            ->andWhere('usm.isActive = 1')
            ->andWhere('s.strCode = :code')->setParameter('code', 'internet')
            ->andWhere('usm.finPeriod = :finPeriod')->setParameter('finPeriod', $finPeriod)
            ->orderBy('usm.id', 'ASC')
            ->getQuery()->getOneOrNullResult();

        if (!$userServMode)
            throw new \InvalidArgumentException('NULL при получении последней активной услуги интернета');

        return $userServMode;
    }


}
