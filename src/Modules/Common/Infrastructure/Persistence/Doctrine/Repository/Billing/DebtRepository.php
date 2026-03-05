<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Application\Dto\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Debt;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DebtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debt::class);
    }

    /** Детализация по списаниям */
    public function findByUser(FilterDto $filterDto, int $uid): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.userId = :uid')->setParameter('uid', $uid)
            ->orderBy('d.discountDateTs', 'DESC')->addOrderBy('d.id', 'DESC')
            ->setFirstResult($filterDto->getOffset())->setMaxResults($filterDto->getLimit())
            ->getQuery()->getResult();
    }

    public function sumByUser(User $user): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt), 0) FROM prod_discount_temp WHERE uid = :uid AND qnt > 0';

        $params = [
            'uid' => $user->getId()
        ];

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, $params);
    }
}
