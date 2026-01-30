<?php

namespace App\Modules\Common\Domain\Aggregates;

use App\Modules\Common\Domain\Model\Contract\ContractModel;
use App\Modules\Common\Domain\Model\Customer\CustomerModel;

class ContractAggregate
{
    public function __construct(
        protected ContractModel $contract,
        protected CustomerModel $customer,
    ) {}

    public function contract(): ContractModel { return $this->contract; }
    public function customer(): CustomerModel { return $this->customer; }
}