<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\WriteOff;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class WriteOffRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WriteOff::class);
    }

    /** История списаний по пользователю (без JOIN-ов) */
    public function findByUser(int $uid, int $limit = 100, int $offset = 0): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.userId = :uid')
            ->setParameter('uid', $uid)
            ->orderBy('w.chargedAt', 'DESC')
            ->addOrderBy('w.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countByUser(int $uid): int
    {
        return (int)$this->createQueryBuilder('w')
            ->select('COUNT(w.id)')
            ->andWhere('w.userId = :uid')
            ->setParameter('uid', $uid)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Расширенная выборка с названием услуги и режимом обслуживания.
     * Возвращает массив (read-model), данные получаем через DBAL JOIN-ы.
     * $includeVoip=false — исключает ps.str_code='voip_charges' (как в вашей процедуре).
     */
    public function findEnrichedByUser(int $uid, int $limit = 100, int $offset = 0, bool $includeVoip = true): array
    {
        $sql = "
        SELECT 
            pdh.id,
            pdh.uid,
            pdh.qnt              AS amount,
            pdh.number           AS units,
            pdh.discount_date    AS discount_date_ts,
            pdh.master,
            pdh.prod_comments    AS comment,
            pdh.bill_before,
            pdh.bill_after,
            pdh.charge_date      AS charged_at,
            pdh.upid             AS payable_id,
            psm.id               AS srvmode_id,
            psm.name             AS srvmode_name,
            ps.id                AS service_id,
            ps.prod_name         AS service_name,
            ps.str_code          AS service_code
        FROM prod_discount_history pdh
        JOIN prod_serv_mode_costs psmc ON psmc.id = pdh.srvmodecost_id
        JOIN prod_serv_modes      psm  ON psm.id = psmc.srvmode_id
        JOIN products_services    ps   ON ps.id = psm.srv_id
        WHERE pdh.uid = :uid
          AND (:includeVoip = 1 OR COALESCE(ps.str_code,'') <> 'voip_charges')
        ORDER BY pdh.charge_date DESC, pdh.id DESC
        LIMIT :limit OFFSET :offset
        ";

        /** @var Connection $c */
        $c = $this->getEntityManager()->getConnection();
        $stmt = $c->prepare($sql);
        $stmt->bindValue('uid', $uid);
        $stmt->bindValue('includeVoip', $includeVoip ? 1 : 0);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Контракт BaseRepository: поддерживаем базовые фильтры через ParameterBag.
     * Параметры:
     *  - uid (int) — фильтр по пользователю
     *  - dateFrom/dateTo ('YYYY-MM-DD' или UNIX int) — по charge_date
     */
    protected function addCriteria(QueryBuilder $qb, ParameterBag $params)
    {
        if ($params->has('uid')) {
            $qb->andWhere('a.userId = :uid')->setParameter('uid', (int)$params->get('uid'));
        }

        if ($params->has('dateFrom')) {
            $from = $params->get('dateFrom');
            $qb->andWhere('a.chargedAt >= :from')
                ->setParameter('from', ctype_digit((string)$from)
                    ? (new \DateTimeImmutable())->setTimestamp((int)$from)
                    : new \DateTimeImmutable($from));
        }

        if ($params->has('dateTo')) {
            $to = $params->get('dateTo');
            $qb->andWhere('a.chargedAt <= :to')
                ->setParameter('to', ctype_digit((string)$to)
                    ? (new \DateTimeImmutable())->setTimestamp((int)$to)
                    : new \DateTimeImmutable($to));
        }
    }
}