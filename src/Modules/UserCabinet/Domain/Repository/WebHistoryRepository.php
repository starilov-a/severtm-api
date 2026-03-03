<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\WebHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class WebHistoryRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private WebActionRepository $actions,
    ) {
        parent::__construct($registry, WebHistory::class);
    }

    /** Быстрый лог по action id (останется в той же транзакции) */
    public function log(int $userId, int $actionId, string $message, int $result = 0, ?string $ip = null, ?\DateTimeImmutable $when = null): int
    {
        $ip ??= 'unknown';
        $when ??= new \DateTimeImmutable();

        return $this->em->getConnection()->executeStatement(
            'INSERT INTO web_log (uid, ip, act_id, act_time, act_message, act_result)
             VALUES (:uid, :ip, :act, :time, :msg, :res)',
            [
                'uid'  => $userId,
                'ip'   => mb_substr($ip, 0, 16),
                'act'  => $actionId,
                'time' => $when->format('Y-m-d H:i:s'),
                'msg'  => $message,
                'res'  => $result,
            ],
            [
                'uid'  => ParameterType::INTEGER,
                'ip'   => ParameterType::STRING,
                'act'  => ParameterType::INTEGER,
                'time' => ParameterType::STRING,
                'msg'  => ParameterType::STRING,
                'res'  => ParameterType::INTEGER,
            ]
        );
    }

    /** Лог по action cid */
    public function logByCid(int $userId, string $cid, string $message, int $result = 0, ?string $ip = null, ?\DateTimeImmutable $when = null): int
    {
        $actionId = $this->actions->getIdByCidOrFail($cid);
        return $this->log($userId, $actionId, $message, $result, $ip, $when);
    }

    /** Последние N записей пользователя */
    public function recentForUser(int $userId, int $limit = 50): array
    {
        return $this->createQueryBuilder('h')
            ->addSelect('a')
            ->join('h.action', 'a')
            ->andWhere('h.userId = :uid')->setParameter('uid', $userId)
            ->orderBy('h.time', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult(); // array<WebHistory>
    }

    /** Поиск по фильтрам (uid/cid/диапазон дат) */
    public function findByFilters(?int $userId, ?string $cid, ?\DateTimeInterface $from, ?\DateTimeInterface $to, int $limit = 100, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('h')->addSelect('a')->join('h.action', 'a')->orderBy('h.time', 'DESC');

        if ($userId !== null) {
            $qb->andWhere('h.userId = :uid')->setParameter('uid', $userId);
        }
        if ($cid !== null) {
            $qb->andWhere('a.cid = :cid')->setParameter('cid', $cid);
        }
        if ($from !== null) {
            $qb->andWhere('h.time >= :from')->setParameter('from', $from);
        }
        if ($to !== null) {
            $qb->andWhere('h.time <= :to')->setParameter('to', $to);
        }

        return $qb->setFirstResult($offset)->setMaxResults($limit)->getQuery()->getResult();
    }
}
