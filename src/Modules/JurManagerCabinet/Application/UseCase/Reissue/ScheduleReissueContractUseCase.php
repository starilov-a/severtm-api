<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Reissue;

use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\ReissueContractDto;
use App\Modules\JurManagerCabinet\Application\UseCase\Contract\CreateJurContractUseCase;
use App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Reissue\ReissueContext;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanReissueContractRuleChain;

class ScheduleReissueContractUseCase
{
    public function __construct(
        protected ManagerRepositoryInterface    $managerRepo,
        protected ContractRepositoryInterface   $contractRepo,

        protected CanReissueContractRuleChain   $canReissueContractRuleChain,

        protected CreateJurContractUseCase      $createJurContractUseCase,
    ) {}

    public function execute(ReissueContractDto $dto): void
    {
        $manager = $this->managerRepo->find($dto->getManagerId());
        $oldContract = $this->contractRepo->find($dto->getContractId());

        // 1. Бизнес проверки
        $result = $this->canReissueContractRuleChain->checkAll(
            new ReissueContext(
                $dto->getNewInn(),
                $oldContract->getInn()
            )
        );
        if (!$result->ok) {
            throw new \DomainException($result->message);
        }

        // 2. Создание договора
        $newContract = $this->createJurContractUseCase->execute(
            new CreateJurContractDto(
                (string)$dto->getNewInn(),
                $dto->getFio(),
                $dto->getLogin(),
                $dto->getPassword(),
                $dto->getEmail(),
                $dto->getPhone(),
            )
        );

        // 3. Выставляем статус "На переоформлении"


        // 4. Заведение задачи на переоформление

    }
}