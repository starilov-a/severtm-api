<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\Replenishment;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class ReplenishmentRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Replenishment::class);
    }

    /** История пополнений по пользователю (Entity) */
    public function findByUser(int $uid, int $limit = 100, int $offset = 0): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.userId = :uid')->setParameter('uid', $uid)
            ->orderBy('t.dateTs', 'DESC')->addOrderBy('t.id', 'DESC')
            ->setFirstResult($offset)->setMaxResults($limit)
            ->getQuery()->getResult();
    }

    public function countByUser(int $uid): int
    {
        return (int)$this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.userId = :uid')->setParameter('uid', $uid)
            ->getQuery()->getSingleScalarResult();
    }

    /** Узкая проекция (массива) для списка — только нужные поля (без гидрации Entity) */
    public function findListItemsByUser(int $uid, int $limit = 100, int $offset = 0): array
    {
        $sql = "
        SELECT
          id,
          uid          AS user_id,
          login,
          qnt          AS amount,
          qnt_currency AS amount_currency,
          currency_id,
          `what`,
          `who`,
          comments,
          real_pay_date AS paid_at_ts,
          `date`        AS booked_at_ts,
          is_automated,
          has_been_transferred
        FROM bills_history
        WHERE uid = :uid
        ORDER BY `date` DESC, id DESC
        LIMIT :limit OFFSET :offset
        ";

        /** @var Connection $c */
        $c = $this->getEntityManager()->getConnection();
        $stmt = $c->prepare($sql);
        $stmt->bindValue('uid', $uid);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /** Итоговая сумма пополнений за период по пользователю */
    public function sumByUser(int $uid, ?int $fromTs = null, ?int $toTs = null): float
    {
        $sql = 'SELECT COALESCE(SUM(qnt),0) FROM bills_history WHERE uid = :uid';
        $params = ['uid' => $uid];

        if ($fromTs !== null) { $sql .= ' AND `date` >= :from'; $params['from'] = $fromTs; }
        if ($toTs   !== null) { $sql .= ' AND `date` <= :to';   $params['to']   = $toTs; }

        return (float) $this->getEntityManager()->getConnection()->fetchOne($sql, $params);
    }

    /**
     * Запись факта пополнения (через DBAL; Entity readOnly).
     * Возвращает ID новой записи.
     */
    public function insertReplenishment(array $data): int
    {
        // ожидаемые ключи: login, uid, amount, currency_id, amount_currency, what, who, comments, date_ts, paid_ts, is_automated, has_been_transferred, refund_comment?
        $row = [
            'login'               => $data['login'],
            'uid'                 => (int)$data['uid'],
            'qnt'                 => (float)$data['amount'],
            'currency_id'         => (int)($data['currency_id'] ?? 0),
            'qnt_currency'        => (float)($data['amount_currency'] ?? $data['amount']),
            'what'                => (string)($data['what'] ?? ''),
            'who'                 => (string)($data['who'] ?? ''),
            'comments'            => (string)($data['comments'] ?? ''),
            'date'                => (int)($data['date_ts'] ?? time()),
            'real_pay_date'       => (int)($data['paid_ts'] ?? time()),
            'is_automated'        => (int)($data['is_automated'] ?? 0),
            'has_been_transferred'=> (int)($data['has_been_transferred'] ?? 0),
            'refund_comment'      => $data['refund_comment'] ?? null,
        ];

        $conn = $this->getEntityManager()->getConnection();
        $conn->insert('bills_history', $row);
        /** @var string|int $id */
        $id = $conn->lastInsertId();

        return (int)$id;
    }

    /** Контракт BaseRepository — поддержка общих фильтров */
    protected function addCriteria(QueryBuilder $qb, \Symfony\Component\HttpFoundation\ParameterBag $params)
    {
        // Alias в BaseRepository обычно 'a'; укажем его явно
        $qb->from(Replenishment::class, 'a');

        if ($params->has('uid')) {
            $qb->andWhere('a.userId = :uid')->setParameter('uid', (int)$params->get('uid'));
        }
        if ($params->has('what')) {
            $qb->andWhere('a.what = :what')->setParameter('what', (string)$params->get('what'));
        }
        if ($params->has('automated')) {
            $qb->andWhere('a.isAutomated = :auto')->setParameter('auto', (int)!!$params->get('automated'));
        }
        if ($params->has('transferred')) {
            $qb->andWhere('a.hasBeenTransferred = :tr')->setParameter('tr', (int)!!$params->get('transferred'));
        }
        if ($params->has('dateFrom')) {
            $from = (int)$params->get('dateFrom'); // ожидаем UNIX-ts
            $qb->andWhere('a.dateTs >= :from')->setParameter('from', $from);
        }
        if ($params->has('dateTo')) {
            $to = (int)$params->get('dateTo');
            $qb->andWhere('a.dateTs <= :to')->setParameter('to', $to);
        }
    }
}