<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\Entity\Replenishment;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ReplenishmentRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReplenishmentRepository extends ServiceEntityRepository implements ReplenishmentRepositoryInterface
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
