<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\Dto\Request\ServiceFilterDto;
use App\Modules\UserCabinet\Domain\Entity\ProductService;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProductServiceRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductServiceRepository extends ServiceEntityRepository implements ProductServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductService::class);
    }

    public function getServicesByFilter(ServiceFilterDto $filter = new ServiceFilterDto()): array
    {
        $qb = $this->createQueryBuilder('p');

        $isVisible = $filter->getVisibleStatus();
        if ($isVisible) {
            $qb->andWhere('p.isVisible = :isVisible')
                ->setParameter('isVisible', (int)$isVisible);
        }

        $qb->orderBy('p.'.$filter->getOrderBy(), $filter->getOrderDir());

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
