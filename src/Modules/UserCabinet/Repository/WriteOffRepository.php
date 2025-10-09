<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\WriteOff;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WriteOffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WriteOff::class);
    }

    /** История списаний по пользователю (без JOIN-ов) */
    public function findByUser(FilterDto $fitler, int $uid): array
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