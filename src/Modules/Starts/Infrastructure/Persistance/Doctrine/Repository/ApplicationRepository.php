<?php

namespace App\Modules\Starts\Infrastructure\Persistance\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Application;
use App\Modules\Starts\Domain\Entity\Application as DomainApplication;
use App\Modules\Starts\Domain\Entity\ApplicationStatus;
use App\Modules\Starts\Domain\Entity\Builder;
use App\Modules\Starts\Domain\RepositoryInterface\ApplicationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApplicationRepository extends ServiceEntityRepository implements ApplicationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, )
    {
        parent::__construct($registry, Application::class);
    }

    /**
     * @param Builder $builder
     * @param array<ApplicationStatus> $statuses
     * @return array|\App\Modules\Starts\Domain\Entity\Application
     */
    public function findListByBuilder(Builder $builder, array $statuses): array
    {
        // TODO: нужно посмотреть как правильно делать запрос к таблице
        $tableApps = $this->createQueryBuilder('a') // 'e' is an alias for the entity
        ->where('a.builder = :builder')
            ->andWhere('a.status IN (:statuses)')
            ->andWhere('a.application_connectdate IS NULL')
            ->setParameter('builder', $builder)
            ->setParameter('statuses', $statuses)
            ->getQuery()
            ->getResult();

        $applicationList = [];
        foreach ($tableApps as $tableApp) {
            $app = new DomainApplication(
                $tableApp->getId(),
                $tableApp->getName(),
                $tableApp->getBuilder(),
                $tableApp->getStatus(),
            );

            $applicationList[] = $app;
        }

        return $applicationList;
    }
}