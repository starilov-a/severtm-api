<?php

namespace App\Modules\Common\Domain\Model\Contract;

use App\Modules\Common\Domain\Entity\User;

class ContractMapper
{
    public function fromUser(User $user): ContractModel
    {
        return new ContractModel(
            id: $user->getId(),
            address: $user->getAddress(),
            blockDate: $user->getBlockDate(),
            isJuridical: (bool)$user->isJuridical(),
        );
    }
}