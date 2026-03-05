<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\CreditHistory;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class CreditHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditHistory::class);
    }

    /**
     * Получить все кредиты за указанный фин. период (обычно текущий).
     *
     * Возвращает массив строк из `credits_history`, без гидрации ORM-сущностей.
     */
    public function findAllForFinPeriod(FinPeriod $finPeriod): array
    {
        $sql = <<<SQL
            SELECT
                credit_date,
                credit_deadline,
                credit_uid,
                credit_sum,
                credit_master,
                credit_bill
            FROM credits_history
            WHERE credit_date >= :start
              AND credit_date <= :end
            ORDER BY credit_date DESC
        SQL;

        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, [
            'start' => $finPeriod->getStartDate()->format('Y-m-d H:i:s'),
            'end' => $finPeriod->getEndDate()->format('Y-m-d H:i:s'),
        ], [
            'start' => ParameterType::STRING,
            'end' => ParameterType::STRING,
        ]);
    }

    /**
     * Проверить наличие хотя бы 1 записи кредита за указанный фин. период.
     */
    public function hasAnyForFinPeriodForUser(User $user, FinPeriod $finPeriod): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM credits_history
            WHERE credit_uid = :uid
              AND credit_date >= :start
              AND credit_date <= :end
            LIMIT 1
        SQL;

        $val = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'uid' => $user->getId(),
            'start' => $finPeriod->getStartDate()->format('Y-m-d H:i:s'),
            'end' => $finPeriod->getEndDate()->format('Y-m-d H:i:s'),
        ], [
            'uid' => ParameterType::INTEGER,
            'start' => ParameterType::STRING,
            'end' => ParameterType::STRING,
        ]);

        return $val !== false;
    }


    public function countByUser(User $user): int
    {
        return $this->countByUserId($user->getId());
    }

    public function countByUserId(int $userId): int
    {
        $sql = 'SELECT COUNT(*) FROM credits_history WHERE credit_uid = :uid';
        return (int)$this->getEntityManager()->getConnection()->fetchOne($sql, [
            'uid' => $userId,
        ], [
            'uid' => ParameterType::INTEGER,
        ]);
    }
}
