<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\Dto\Request\CreditHistoryLogDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\CreditHistory;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\CreditHistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class CreditHistoryRepository extends ServiceEntityRepository implements CreditHistoryRepositoryInterface
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

    /**
     * Создать запись в `credits_history` без ORM-сущности.
     * Возвращает количество затронутых строк (обычно 1).
     */
    public function insertLog(CreditHistoryLogDto $dto): int
    {
        $sql = <<<SQL
            INSERT INTO credits_history (
                credit_date,
                credit_deadline,
                credit_uid,
                credit_sum,
                credit_master,
                credit_bill
            ) VALUES (
                :credit_date,
                :credit_deadline,
                :credit_uid,
                :credit_sum,
                :credit_master,
                :credit_bill
            )
        SQL;

        return $this->getEntityManager()->getConnection()->executeStatement($sql, [
            'credit_date' => $dto->getCreditDate()->format('Y-m-d H:i:s'),
            'credit_deadline' => $dto->getCreditDeadline()->format('Y-m-d'),
            'credit_uid' => $dto->getUser()->getId(),
            'credit_sum' => $dto->getCreditSum(),
            'credit_master' => $dto->getMaster()->getId(),
            'credit_bill' => $dto->getCreditBill(),
        ], [
            'credit_date' => ParameterType::STRING,
            'credit_deadline' => ParameterType::STRING,
            'credit_uid' => ParameterType::INTEGER,
            'credit_sum' => ParameterType::STRING,
            'credit_master' => ParameterType::INTEGER,
            'credit_bill' => ParameterType::STRING,
        ]);
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
