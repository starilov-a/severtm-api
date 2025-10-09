<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Debt;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
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

    public function sumByUser(int $uid): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt), 0) FROM prod_discount_temp WHERE uid = :uid AND qnt > 0';

        $params = [
            'uid' => $uid
        ];

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, $params);
    }
}
