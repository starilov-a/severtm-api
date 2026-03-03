<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayableType;

interface UserPayableTypeRepositoryInterface extends RepositoryInterface
{
    public function findOneByCode(string $code): ?UserPayableType;
}
