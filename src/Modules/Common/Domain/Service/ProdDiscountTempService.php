<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\ProdDiscountTemp;
use App\Modules\Common\Domain\Repository\ProdDiscountTempRepository;

class ProdDiscountTempService
{
    public function __construct(
        protected ProdDiscountTempRepository $discountTempRepo,
    ) {
    }

    /**
     * Заглушка: создание записи prod_discount_temp.
     */
    public function create(): ProdDiscountTemp
    {
        $entity = new ProdDiscountTemp();

        $em = $this->discountTempRepo->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $entity;
    }
}

