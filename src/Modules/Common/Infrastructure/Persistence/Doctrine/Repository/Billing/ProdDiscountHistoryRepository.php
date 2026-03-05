<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Application\Dto\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdDiscountHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdDiscountHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdDiscountHistory::class);
    }

    /** История списаний по пользователю (без JOIN-ов) */
    public function findByUser(int $uid, FilterDto $fitler): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.userId = :uid')
            ->setParameter('uid', $uid)
            ->orderBy('w.chargedAt', 'DESC')
            ->addOrderBy('w.id', 'DESC')
            ->setFirstResult($fitler->getOffset())
            ->setMaxResults($fitler->getLimit())
            ->getQuery()
            ->getResult();
    }
}