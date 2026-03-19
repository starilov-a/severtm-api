<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;

interface ContractReissueProcessRepositoryInterface
{
    public function getReissueProcessByContract(Contract $contract): ContractReissueProcess;

    public function findScheduledByContract(Contract $contract): ContractReissueProcess;
}