<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Customer;


use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\ReregestrationContractLegacyDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanReregestrationContractRuleChain;

class ReregestrationContractLegacyUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface          $webActionRepo,
        protected ManagerRepositoryInterface            $managerRepo,
        protected ContractRepositoryInterface           $contractRepo,

        protected CreateJurContractUseCase              $createJurContractUseCase,

        protected CanReregestrationContractRuleChain    $ruleChain,
    ) {}

    public function handle(ReregestrationContractLegacyDto $dto): Contract
    {
        $manager = $this->managerRepo->find($dto->getManagerId());
        $webAction = $this->webActionRepo->findIdByCid('W3_FIRST_FREEZE_ACCOUNT');
        $oldContract = $this->contractRepo->find($dto->getContractId());

        // 1. Бизнес логика
        $this->ruleChain->checkAll(
            new ReregestractionContext(
                $webAction,
                $manager,
                $oldContract,
                $dto->getNewInn(),
                $dto->getLogin()
            )
        );



        // 2. Наполнение dto для создания нового договора
        $createJurContractDto = new CreateJurContractDto();

        // 3. Создание нового договора через Starts
        $contract = $this->createJurContractUseCase->handle($dto);

        // 4. Перенос существующих настроек


        // 5. Закрытие прошлого договора


        // 6. Логирование

        return $contract;

    }
}