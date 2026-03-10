<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableParameter;

interface UserPayableParameterRepositoryInterface extends RepositoryInterface
{
    public function save(UserPayableParameter $userPayableParameter): UserPayableParameter;
}
