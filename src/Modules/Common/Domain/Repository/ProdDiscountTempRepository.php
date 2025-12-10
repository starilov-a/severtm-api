<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\ProdDiscountTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdDiscountTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdDiscountTemp::class);
    }
}

