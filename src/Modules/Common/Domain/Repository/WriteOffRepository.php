<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\WriteOff;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WriteOffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WriteOff::class);
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