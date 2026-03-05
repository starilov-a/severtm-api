<?php

namespace App\Modules\Starts\Infrastructure\Persistance\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ApplicationStatus;
use App\Modules\Starts\Domain\RepositoryInterface\ApplicationStatusRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApplictionStatusRepository extends ServiceEntityRepository implements ApplicationStatusRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationStatus::class);
    }
}