<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayable;

interface UserPayableRepositoryInterface extends RepositoryInterface
{
    public function save(UserPayable $payable): UserPayable;
}
