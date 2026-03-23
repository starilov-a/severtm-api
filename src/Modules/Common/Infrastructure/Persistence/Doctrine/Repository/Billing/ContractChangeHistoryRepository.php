<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ContractChangeHistory;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ContractChangeHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractChangeHistory::class);
    }

    public function save(ContractChangeHistory $history): ContractChangeHistory
    {
        $this->getEntityManager()->persist($history);
        $this->getEntityManager()->flush();

        return $history;
    }

    /**
     * @return ContractChangeHistory[]
     */
    public function findRecentByUser(User $user, int $limit = 10): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.user = :user')->setParameter('user', $user)
            ->orderBy('h.timeStamp', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ContractChangeHistory[]
     */
    public function findRecentByMaster(User $master, int $limit = 10): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.masterUser = :master')->setParameter('master', $master)
            ->orderBy('h.timeStamp', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
