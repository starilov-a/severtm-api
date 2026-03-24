<?php

namespace App\Modules\JurManagerCabinet\Domain\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;

class ContractService
{
    public function __construct(
        protected ContractRepositoryInterface $contractRepo,
    ) {}
    public function getContractById(int $contractId): Contract
    {
        $contract = $this->contractRepo->find($contractId);

        if (is_null($contract)) {
            throw new BusinessException('Договор с таким номером не найден');
        }

        return $contract;
    }
}