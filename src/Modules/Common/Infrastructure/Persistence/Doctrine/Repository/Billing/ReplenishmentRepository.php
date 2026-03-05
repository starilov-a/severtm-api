<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Application\Dto\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Replenishment;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReplenishmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Replenishment::class);
    }

    /** История пополнений по пользователю (Entity) */
    public function findByUser(User $user, FilterDto $filterDto): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')->setParameter('user', $user)
            ->orderBy('t.dateTs', 'DESC')->addOrderBy('t.id', 'DESC')
            ->setFirstResult($filterDto->getOffset())->setMaxResults($filterDto->getLimit())
            ->getQuery()->getResult();
    }

    /** Итоговая сумма пополнений за период по пользователю */
    public function sumByUser(int $uid): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt), 0) FROM bills_history WHERE uid = :uid';

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, [ 'uid' => $uid]);
    }
}
