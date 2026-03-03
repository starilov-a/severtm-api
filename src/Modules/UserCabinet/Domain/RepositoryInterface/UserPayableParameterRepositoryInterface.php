<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayableParameter;

interface UserPayableParameterRepositoryInterface extends RepositoryInterface
{
    public function save(UserPayableParameter $userPayableParameter): UserPayableParameter;
}
