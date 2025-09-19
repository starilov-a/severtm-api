<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\Tariff;
use App\Modules\UserCabinet\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

class TariffRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function getCurrentForUser(int $uid): ?Tariff
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
            ->from(User::class, 'u')
            ->leftJoin('u.currentTariff', 't')
            ->andWhere('u.id = :uid')->setParameter('uid', $uid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getNextForUser(int $uid): ?Tariff
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
            ->from(User::class, 'u')
            ->leftJoin('u.nextTariff', 't')
            ->andWhere('u.id = :uid')->setParameter('uid', $uid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function issetForAddress(int $tariffId, int $addressId): bool
    {

    }
}