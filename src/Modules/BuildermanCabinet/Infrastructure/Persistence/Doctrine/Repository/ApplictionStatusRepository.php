<?php

namespace App\Modules\BuildermanCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ApplicationStatus;
use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus as DomainApplicationStatus;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\ApplicationStatusRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApplictionStatusRepository extends ServiceEntityRepository implements ApplicationStatusRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationStatus::class);
    }

    public function findAll(): array
    {
        /** @var array<ApplicationStatus> $tableStatuses */
        $tableStatuses = parent::findAll();

        $statusesList = [];
        foreach ($tableStatuses as $tableStatus) {
            $status = new DomainApplicationStatus(
                $tableStatus->getName(),
                $tableStatus->getValue()
            );
            $statusesList[] = $status;
        }

        return $statusesList;
    }
}