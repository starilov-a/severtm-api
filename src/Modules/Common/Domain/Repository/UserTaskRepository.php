<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Entity\UserTaskType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTask::class);
    }

    public function hasTaskWithState(User $user, UserTaskType $type, UserTaskState $state): bool
    {
        $qb = $this->createQueryBuilder('ut')
            ->select('1')
            ->andWhere('ut.user = :user')->setParameter('user', $user)
            ->andWhere('ut.type = :type')->setParameter('type', $type)
            ->andWhere('ut.state = :state')->setParameter('state', $state)
            ->setMaxResults(1);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }

    public function hasTaskWithStateInPeriod(
        User $user,
        UserTaskType $type,
        UserTaskState $state,
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): bool {
        $qb = $this->createQueryBuilder('ut')
            ->select('1')
            ->andWhere('ut.user = :user')->setParameter('user', $user)
            ->andWhere('ut.type = :type')->setParameter('type', $type)
            ->andWhere('ut.state = :state')->setParameter('state', $state)
            ->andWhere('ut.startTime BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setMaxResults(1);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
