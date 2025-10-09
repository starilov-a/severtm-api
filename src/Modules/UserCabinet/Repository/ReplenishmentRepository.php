<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Replenishment;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReplenishmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Replenishment::class);
    }

    /** История пополнений по пользователю (Entity) */
    public function findByUser(FilterDto $filterDto, int $uid): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.userId = :uid')->setParameter('uid', $uid)
            ->orderBy('t.dateTs', 'DESC')->addOrderBy('t.id', 'DESC')
            ->setFirstResult($filterDto->getOffset())->setMaxResults($filterDto->getLimit())
            ->getQuery()->getResult();
    }

    /** Итоговая сумма пополнений за период по пользователю */
    public function sumByUser(int $uid): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt), 0) FROM bills_history WHERE uid = :uid';
        $params = [
            'uid' => $uid
        ];

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, $params);
    }
}