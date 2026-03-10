<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdDiscountTemp;

interface ProdDiscountTempRepositoryInterface extends RepositoryInterface
{
    public function save(ProdDiscountTemp $discountTemp): ProdDiscountTemp;
}
