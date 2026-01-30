<?php

namespace App\Modules\Common\Domain\AggregateService;

use App\Modules\Common\Domain\Aggregates\CustomerProfileAggregate;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Model\Contract\ContractMapper;
use App\Modules\Common\Domain\Model\Contract\ContractModel;
use App\Modules\Common\Domain\Model\Customer\CustomerResolver;
use App\Modules\Common\Domain\Repository\UserRepository;

class CustomerProfileAggregateService
{
    public function __construct(
        protected UserRepository $userRepo,

        protected CustomerResolver $customerResolver,

        protected ContractMapper $contractMapper
    ) {}

    public function getCustomerProfileByUserId(User $user): CustomerProfileAggregate
    {
        $customer = $this->customerResolver->fromUser($user);
        $contracts = $this->userRepo->findAllContractIds($user);

        return new CustomerProfileAggregate(
            customer: $customer,
            contracts: $contracts,
            totalContracts: count($contracts),
        );
    }
// TODO: сделать получение по ИНН для юриков

//    public function getCustomerProfileByContractId(int $contractId): CustomerProfileAggregate
//    {
//
//    }

    /**
     * @return ContractModel[]
     */
    public function getAllContracts(CustomerProfileAggregate $profile): array
    {
        if ($profile->getTotalContracts() <= 1)
            throw new \LogicException('Клиент имеет только 1 договор / не имеет договоров!');

        $contractsModels = [];
        foreach ($profile->getContracts() as $contract) {
            $tableUser = $this->userRepo->find($contract);
            $contractsModels[] = $this->contractMapper->fromUser($tableUser);
        }
        return $contractsModels;
    }

    public function getSingleContract(CustomerProfileAggregate $profile): ContractModel
    {
        if ($profile->getTotalContracts() > 1)
            throw new \LogicException('Клиент имеет множество договор!');

        $tableUser = $this->userRepo->find($profile->getContracts()[0]);
        return $this->contractMapper->fromUser($tableUser);
    }
}