<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayable;

interface UserPayableRepositoryInterface extends RepositoryInterface
{
    public function save(UserPayable $payable): UserPayable;
}
