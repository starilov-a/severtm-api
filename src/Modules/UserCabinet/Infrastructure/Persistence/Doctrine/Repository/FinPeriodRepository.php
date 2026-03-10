<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class FinPeriodRepository extends ServiceEntityRepository implements FinPeriodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinPeriod::class);
    }
    function getNext(): ?FinPeriod
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $finPeriod=  $qb->select('n')
            ->from(FinPeriod::class, 'n')
            ->andWhere('n.startDate > (SELECT c.startDate FROM ' . FinPeriod::class . ' c WHERE c.isCurrent = true)')
            ->orderBy('n.startDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        if (!$finPeriod)
            throw new \InvalidArgumentException('NULL при получении следующего фин. периода');

        return $finPeriod;
    }

    public function getCurrent(): ?FinPeriod
    {
        $finPeriod = $this->createQueryBuilder('fp')
            ->andWhere('fp.isCurrent = :cur')->setParameter('cur', true)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        if (!$finPeriod)
            throw new \InvalidArgumentException('NULL при получении текущего фин. периода');

        return $finPeriod;
    }

    /**
     * Найти первый будущий fid, в котором у пользователя уже есть USM-записи.
     */
    public function findFirstFutureFidWithUserModes(int $userId, int $baseFid): ?int
    {
        $sql = <<<SQL
        SELECT um.fid
        FROM fin_periods f
        JOIN fin_periods future ON future.start_date > f.start_date
        JOIN user_serv_modes um ON um.fid = future.id
        WHERE f.id = :baseFid
          AND um.uid = :uid
        ORDER BY future.start_date ASC
        LIMIT 1
        SQL;

        $conn = $this->getEntityManager()->getConnection();
        $fid = $conn->fetchOne($sql, [
            'baseFid' => $baseFid,
            'uid'     => $userId,
        ], [
            'baseFid' => ParameterType::INTEGER,
            'uid'     => ParameterType::INTEGER,
        ]);

        return $fid !== false ? (int)$fid : null;
    }

    /**
     * Проверка "fid действительно в будущем" относительно текущего (замена f_fid_is_in_the_future)
     */
    public function isFidInFuture(int $fid): bool
    {
        $sql = <<<SQL
        SELECT 1
        FROM fin_periods nowp
        JOIN fin_periods fut ON fut.start_date > nowp.start_date AND fut.id = :fid
        WHERE nowp.is_current = 1
        LIMIT 1
        SQL;

        $conn = $this->getEntityManager()->getConnection();
        return false !== $conn->fetchOne($sql, ['fid' => $fid], ['fid' => ParameterType::INTEGER]);
    }

    /**
     * Очистить будущие данные пользователя начиная с начала заданного будущего fid.
     * аналог __p_clean_future_fin_period.
     *
     */
    public function cleanFutureFromFinId(int $userId, int $fromFid): int
    {
        $conn = $this->getEntityManager()->getConnection();

        // Убедимся, что этот fid действительно в будущем
        if (!$this->isFidInFuture($fromFid))
            return true;

        // Возьмём дату старта этого периода
        $startDate = $conn->fetchOne(
            'SELECT start_date FROM fin_periods WHERE id = :fid',
            ['fid' => $fromFid],
            ['fid' => ParameterType::INTEGER]
        );
        if (!$startDate)
            return true;

        // Удаляем всё в будущих периодах начиная с этой даты
        $params = [
            'uid'       => $userId,
            'startDate' => $startDate,
        ];
        $types = [
            'uid'       => ParameterType::INTEGER,
            'startDate' => ParameterType::STRING,
        ];

        //TODO: Добавить логи - что удалилось

        // user_discounts
        $conn->executeStatement(<<<SQL
            DELETE ud
            FROM fin_periods f
            JOIN user_discounts ud ON ud.fid = f.id
            WHERE ud.uid = :uid
              AND f.start_date >= :startDate
        SQL, $params, $types);

        // user_payables
        $conn->executeStatement(<<<SQL
            DELETE up
            FROM fin_periods f
            JOIN user_payables up ON up.fid = f.id
            WHERE up.uid = :uid
              AND f.start_date >= :startDate
        SQL, $params, $types);

        // user_serv_modes
        $conn->executeStatement(<<<SQL
            DELETE um
            FROM fin_periods f
            JOIN user_serv_modes um ON um.fid = f.id
            WHERE um.uid = :uid
              AND f.start_date >= :startDate
        SQL, $params, $types);

        return true;
    }

    /**
     * Чистка всех услуг ПОСЛЕ указанного финансового периода.
     *
     * (Этот метод в правильном репо? - думаю да)
     */
    public function clearForFinPeriod(int $fid, int $userId): ?bool
    {
        // фин. период после указанного. По нему все удаления
        $targetFid = $this->findFirstFutureFidWithUserModes($userId, $fid);

        if ($targetFid === null)
            return true; //TODO:Добавить лог

        return $this->cleanFutureFromFinId($userId, $targetFid);
    }
}