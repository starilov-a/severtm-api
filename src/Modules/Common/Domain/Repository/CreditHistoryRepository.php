<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\CreditHistory;
use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CreditHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditHistory::class);
    }

    /**
     * Получить все кредиты за указанный фин. период (обычно текущий).
     *
     * @return CreditHistory[]
     */
    public function findAllForFinPeriod(FinPeriod $finPeriod): array
    {
        return $this->createQueryBuilder('ch')
            ->andWhere('ch.creditDate >= :start')->setParameter('start', $finPeriod->getStartDate())
            ->andWhere('ch.creditDate <= :end')->setParameter('end', $finPeriod->getEndDate())
            ->orderBy('ch.creditDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Проверить наличие хотя бы 1 записи кредита за указанный фин. период.
     */
    public function hasAnyForFinPeriodForUser(User $user, FinPeriod $finPeriod): bool
    {
        $qb = $this->createQueryBuilder('ch')
            ->select('1')
            ->andWhere('ch.user = :user')->setParameter('user', $user)
            ->andWhere('ch.creditDate >= :start')->setParameter('start', $finPeriod->getStartDate())
            ->andWhere('ch.creditDate <= :end')->setParameter('end', $finPeriod->getEndDate())
            ->setMaxResults(1);

        return (bool)$qb->getQuery()->getOneOrNullResult();
    }
}

