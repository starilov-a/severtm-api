<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserTaskState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserTaskStateRepository extends ServiceEntityRepository implements UserTaskStateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTaskState::class);
    }
}
