<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\ProdServMode;
use App\Modules\UserCabinet\Entity\ProductService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProdServModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdServMode::class);
    }

    /** @return ProdServMode[] */
    public function findVisibleByServiceCode(string $serviceStrCode): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.service', 's')
            ->andWhere('s.strCode = :code')->setParameter('code', $serviceStrCode)
            ->andWhere('m.isVisible = 1')
            ->orderBy('m.priority', 'ASC')
            ->getQuery()->getResult();
    }

    public function findOneByStrCode(string $modeCode): ?ProdServMode
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.strCode = :c')->setParameter('c', $modeCode)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
