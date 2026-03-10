<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Dto\Request\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;

interface ReplenishmentRepositoryInterface extends RepositoryInterface
{
    /** История пополнений по пользователю */
    public function findByUser(User $user, FilterDto $filterDto): array;
}
