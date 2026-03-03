<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\ProdDiscountTemp;

interface ProdDiscountTempRepositoryInterface extends RepositoryInterface
{
    public function save(ProdDiscountTemp $discountTemp): ProdDiscountTemp;
}
