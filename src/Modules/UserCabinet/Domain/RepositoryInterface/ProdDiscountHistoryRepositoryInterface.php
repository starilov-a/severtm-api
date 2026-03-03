<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\ProdDiscountHistory;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

interface ProdDiscountHistoryRepositoryInterface extends RepositoryInterface
{
    public function findByUser(int $uid, FilterDto $fitler): array;
}
