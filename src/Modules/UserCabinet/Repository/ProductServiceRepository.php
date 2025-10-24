<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\ProductService;
use App\Modules\UserCabinet\Service\Dto\Request\ServiceFilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductService::class);
    }

    public function getServices(ServiceFilterDto $filter = new ServiceFilterDto()): array
    {
        $qb = $this->createQueryBuilder('p');

        $isVisible = $filter->getVisibleStatus();
        if ($isVisible) {
            $qb->andWhere('p.isVisible > :isVisible')
                ->setParameter('isVisible', (int)$isVisible);
        }


        return $qb->getQuery()->getResult();
    }

    public function findByStrCode(string $code): ?ProductService
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.strCode = :code')->setParameter('code', $code)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /** @return ProductService[] */
    public function findVisible(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isVisible = 1')
            ->orderBy('s.priority', 'DESC')
            ->getQuery()->getResult();
    }
}
