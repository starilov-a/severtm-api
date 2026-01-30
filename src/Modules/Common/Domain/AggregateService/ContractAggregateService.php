<?php

namespace App\Modules\Common\Domain\AggregateService;

use App\Modules\Common\Domain\Aggregates\ContractAggregate;
use App\Modules\Common\Domain\Model\Contract\ContractMapper;
use App\Modules\Common\Domain\Model\Customer\CustomerResolver;
use App\Modules\Common\Domain\Repository\UserRepository;

class ContractAggregateService
{
    public function __construct(
        protected UserRepository    $userRepository,
        protected CustomerResolver  $customerResolver,
        protected ContractMapper    $contractMapper
    ) {}
    public function getCustomerByUserId(int $id): ContractAggregate
    {
        $tableUser = $this->userRepository->find($id);
        return new ContractAggregate(
            $this->contractMapper->fromUser($tableUser),
            $this->customerResolver->fromUser($tableUser)
        );
    }

//    public function getCustomerByContractId(int $id): ContractAggregate
//    {
//
//    }
}