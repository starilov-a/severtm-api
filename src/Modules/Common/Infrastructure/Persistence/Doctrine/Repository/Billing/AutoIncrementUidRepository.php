<?php

declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\AutoIncrementUid;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\PsGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class AutoIncrementUidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutoIncrementUid::class);
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
        /** @var AutoIncrementUid|null $counter */
        $counter = $this->findOneBy([], ['id' => 'DESC']);

        if ($counter === null) {
            throw new RuntimeException('Row in organisations_uid was not found.');
        }

        $currentId = $counter->getValue();

        if ($currentId <= 0) {
            throw new RuntimeException('Invalid value in organisations_uid.');
        }

        $counter->increment();

        $em = $this->getEntityManager();
        $em->persist($counter);
        $em->flush();

        return $currentId;
    }
}