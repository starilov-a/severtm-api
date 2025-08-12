<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\Debt;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class DebtRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debt::class);
    }

    /** Все долги пользователя (Entity) */
    public function findByUser(int $uid, int $limit = 200, int $offset = 0): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.userId = :uid')->setParameter('uid', $uid)
            ->orderBy('d.discountDateTs', 'DESC')->addOrderBy('d.id', 'DESC')
            ->setFirstResult($offset)->setMaxResults($limit)
            ->getQuery()->getResult();
    }

    public function sumByUser(int $uid, ?int $fromTs = null, ?int $toTs = null): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt),0) FROM prod_discount_temp WHERE uid = :uid AND qnt > 0';
        $params = ['uid' => $uid];

        if ($fromTs !== null) { $sql .= ' AND discount_date >= :from'; $params['from'] = $fromTs; }
        if ($toTs   !== null) { $sql .= ' AND discount_date <= :to';   $params['to']   = $toTs; }

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, $params);
    }

    /** Контракт BaseRepository — общие фильтры */
    protected function addCriteria(QueryBuilder $qb, ParameterBag $params)
    {
        // Предполагаем, что BaseRepository создаёт QB с алиасом 'a'
        if ($params->has('uid')) {
            $qb->andWhere('a.userId = :uid')->setParameter('uid', (int)$params->get('uid'));
        }
        if ($params->has('productCode')) {
            $qb->andWhere('a.productCode = :pc')->setParameter('pc', (int)$params->get('productCode'));
        }
        if ($params->has('status')) {
            $qb->andWhere('a.status = :st')->setParameter('st', (int)$params->get('status'));
        }
        if ($params->has('dateFrom')) {
            $qb->andWhere('a.discountDateTs >= :from')->setParameter('from', (int)$params->get('dateFrom'));
        }
        if ($params->has('dateTo')) {
            $qb->andWhere('a.discountDateTs <= :to')->setParameter('to', (int)$params->get('dateTo'));
        }
    }
}
