<?php

declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\AutoIncrementUid;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class AutoIncrementUidRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Возвращает текущий uid и сразу увеличивает счетчик на 1.
     *
     * Аналог процедуры p_insert_into_users_organisation:
     * 1. select id from organisations_uid
     * 2. update organisations_uid set id = id + 1
     */
    public function getAndIncreaseNextUserId(): int
    {
        return $this->entityManager->wrapInTransaction(function (): int {
            /** @var AutoIncrementUid|null $counter */
            $counter = $this->entityManager->find(
                AutoIncrementUid::class,
                1,
                LockMode::PESSIMISTIC_WRITE
            );

            if ($counter === null) {
                throw new RuntimeException('Row in organisations_uid was not found.');
            }

            $currentId = $counter->getValue();

            if ($currentId <= 0) {
                throw new RuntimeException('Invalid value in organisations_uid.');
            }

            $counter->increment();

            $this->entityManager->persist($counter);
            $this->entityManager->flush();

            return $currentId;
        });
    }
}