<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\UserTaskState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserTaskStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTaskState::class);
    }
}
