<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Contract;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractCreationRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanCreateJurContractRuleChain;
use phpDocumentor\Reflection\Types\Object_;

class CreateJurContractUseCase
{
    public function __construct(
        private ContractCreationRepositoryInterface $contractCreationRepository,

        private CanCreateJurContractRuleChain $ruleChain,
    ) {}

    public function execute(CreateJurContractDto $dto): Contract
    {

        // 1. Бизнес проверки
        $result = $this->ruleChain->checkAll(new Object_());
        if (!$result->ok) {
            throw new BusinessException($result->message);
        }

        // 2. Создание договора
        $contract = $this->contractCreationRepository->create($dto);

        return $contract;
    }
}