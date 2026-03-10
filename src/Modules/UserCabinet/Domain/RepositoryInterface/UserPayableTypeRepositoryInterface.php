<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableType;

interface UserPayableTypeRepositoryInterface extends RepositoryInterface
{
    public function findOneByCode(string $code): ?UserPayableType;
}
