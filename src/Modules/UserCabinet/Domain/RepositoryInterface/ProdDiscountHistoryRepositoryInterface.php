<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Dto\Request\FilterDto;

interface ProdDiscountHistoryRepositoryInterface extends RepositoryInterface
{
    public function findByUser(int $uid, FilterDto $fitler): array;
}
