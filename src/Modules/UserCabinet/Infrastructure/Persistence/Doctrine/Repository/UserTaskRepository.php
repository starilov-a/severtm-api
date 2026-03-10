<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskState;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserTaskRepository extends ServiceEntityRepository implements UserTaskRepositoryInterface
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

    public function save(UserTask $userTask): UserTask
    {
        $this->getEntityManager()->persist($userTask);
        $this->getEntityManager()->flush();

        return $userTask;
    }
}
