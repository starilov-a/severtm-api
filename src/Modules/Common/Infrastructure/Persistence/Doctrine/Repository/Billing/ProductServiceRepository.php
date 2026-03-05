<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProductService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductService::class);
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
