<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Balance;
use App\Modules\UserCabinet\Service\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception as DBALException;

class BalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Balance::class);
    }

    /** Возвращает баланс пользователя или бросает 404-бизнес-исключение */
    public function get(int $uid): Balance
    {
        $balance = $this->find($uid);
        if (!$balance) {
            throw new UserNotFoundException($uid);
        }
        return $balance;
    }

    /**
     * Увеличивает баланс на $delta. Возвращает новое значение.
     */
    public function increase(int $uid, int $delta): int
    {
        if ($delta < 0) {
            throw new \InvalidArgumentException('Изменение баланса должно быть >= 0');
        }

        $conn = $this->getEntityManager()->getConnection();
        $conn->beginTransaction();
        try {
            $conn->executeStatement(
                'UPDATE users SET bill = bill + :delta WHERE id = :id',
                ['delta' => $delta, 'id' => $uid],
                ['delta' => \PDO::PARAM_INT, 'id' => \PDO::PARAM_INT]
            );

            $new = (int)$conn->fetchOne('SELECT bill FROM users WHERE id = :id', ['id' => $uid]);
            if ($new === 0 && $this->find($uid) === null) {
                throw new UserNotFoundException($uid);
            }

            $conn->commit();
            return $new;
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e instanceof DBALException ? $e : new DBALException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Уменьшает баланс на $delta (>=0) с защитой от ухода в минус.
     * Возвращает новое значение. Если денег не хватает — кидает бизнес-ошибку.
     */
    public function decrease(int $uid, int $delta): int
    {
        if ($delta < 0) {
            throw new \InvalidArgumentException('Изменение баланса должно быть >= 0');
        }

        $conn = $this->getEntityManager()->getConnection();
        $conn->beginTransaction();
        try {
            // Гарантия отсутствия минуса — условие в UPDATE
            $affected = $conn->executeStatement(
                'UPDATE users SET bill = bill - :delta WHERE id = :id AND bill >= :delta',
                ['delta' => $delta, 'id' => $uid],
                ['delta' => \PDO::PARAM_INT, 'id' => \PDO::PARAM_INT]
            );

            if ($affected === 0) {
                // либо пользователя нет, либо не хватает средств
                // различим это двумя быстрыми проверками:
                $exists = (bool)$conn->fetchOne('SELECT 1 FROM users WHERE id = :id', ['id' => $uid]);
                if (!$exists)
                    throw new UserNotFoundException($uid);
                // отдельное бизнес-исключение под «недостаточно средств»
                throw new \Exception('Недостаточно средств на счёте', 'BALANCE_NOT_ENOUGH');
            }

            $new = (int)$conn->fetchOne('SELECT bill FROM users WHERE id = :id', ['id' => $uid]);
            $conn->commit();
            return $new;
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e instanceof DBALException ? $e : new DBALException($e->getMessage(), $e->getCode(), $e);
        }
    }

}