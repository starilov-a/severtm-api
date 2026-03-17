<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractCreationRepositoryInterface;

class ContractCreationRepository implements ContractCreationRepositoryInterface
{

    public function create(CreateJurContractDto $contractDto): Contract
    {
        // 1. Создание User
        $tableUser = new User();

        $tableUser->setLogin();
        $tableUser->setPassword();
        $tableUser->setDistrict();
        $tableUser->setPassport();
        $tableUser->setAddress();

        // 2. Создание WebUser

        $contract = new Contract();

        return $contract;
    }
}