<?php

namespace App\Modules\BuildermanCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\BuildermanCabinet\Application\Dto\Response\ApplicationListItemDto;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\BuilderRepositoryInterface;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Application;
use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;
use App\Modules\BuildermanCabinet\Domain\Entity\Builder;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\ApplicationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApplicationRepository extends ServiceEntityRepository implements ApplicationRepositoryInterface
{
    protected BuilderRepositoryInterface $builderRepo;
    public function __construct(ManagerRegistry $registry, BuilderRepositoryInterface $builderRepo)
    {
        parent::__construct($registry, Application::class);
        $this->builderRepo = $builderRepo;
    }

    /**
     * @param Builder $builder
     * @param ApplicationStatus[] $statuses
     * @return ApplicationListItemDto[] array
     */
    public function findActiveAppListByBuilder(Builder $builder, array $statuses): array
    {
        $statusCodes = array_map(function($status) {
            return $status->getStrCode();
        }, $statuses);

        $qb = $this->createQueryBuilder('a');
        $tableApps = $qb->andWhere('a.builder = :builder')
            ->join('a.status', 's')
            ->join('a.address', 'ad')
            ->andWhere('a.connectDate IS NULL')
            ->andWhere($qb->expr()->in('s.value', ':statusCodes'))
            ->setParameter('builder', $builder->getLogin())
            ->setParameter('statusCodes', $statusCodes)
            ->getQuery()
            ->getResult();


        $applicationList = [];
        foreach ($tableApps as $tableApp) {
            $applicationList[] = new ApplicationListItemDto(
                $tableApp->getId(),
                $tableApp->getAddress()->getName(),
                $tableApp->getBuilder(),
                $tableApp->getStatus()->getValue()
            );
        }

        return $applicationList;
    }
}