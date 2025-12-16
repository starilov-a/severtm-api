<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserServModeDto;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Dto\Request\UserServModeDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
    public function findActiveByModeAndUser(ProdServMode $mode, User $user): ?UserServMode
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

    /**
     * Найти UserServMode по usm_id с подгруженным пользователем.
     */
    public function findOneWithUserById(int $usmId): ?UserServMode
    {
        return $this->createQueryBuilder('usm')
            ->leftJoin('usm.user', 'u')
            ->addSelect('u')
            ->andWhere('usm.id = :id')->setParameter('id', $usmId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function hasActiveMultiPeriodModes(int $userId, int $finPeriodId): bool
    {
        $qb = $this->createQueryBuilder('usm')
            ->select('1')
            ->join('usm.mode', 'm')
            ->join('usm.finPeriod', 'f')
            ->andWhere('usm.user = :uid')->setParameter('uid', $userId)
            ->andWhere('f.id = :fid')->setParameter('fid', $finPeriodId)
            ->andWhere('usm.isActive = 1')
            ->andWhere('m.periods > 1')
            ->setMaxResults(1);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
