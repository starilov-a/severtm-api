<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdDiscountTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdDiscountTempRepository extends ServiceEntityRepository
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

