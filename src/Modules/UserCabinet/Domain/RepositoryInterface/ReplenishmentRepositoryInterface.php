<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

interface ReplenishmentRepositoryInterface extends RepositoryInterface
{
    /** История пополнений по пользователю */
    public function findByUser(User $user, FilterDto $filterDto): array;
}
