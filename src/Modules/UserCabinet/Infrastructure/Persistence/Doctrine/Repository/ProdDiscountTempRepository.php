<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdDiscountTempRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\ProdDiscountTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdDiscountTempRepository extends ServiceEntityRepository implements ProdDiscountTempRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdDiscountTemp::class);
    }

    public function save(ProdDiscountTemp $discountTemp): ProdDiscountTemp
    {
        $this->getEntityManager()->persist($discountTemp);
        $this->getEntityManager()->flush();

        return $discountTemp;
    }
}

