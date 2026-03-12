<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Reissue;


use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\ReregestrationContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\ReregestrationContractLegacyDto;
use App\Modules\JurManagerCabinet\Application\UseCase\Contract\CreateJurContractUseCase;
use App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Contract\ReregestractionContext;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanReregestrationContractRuleChain;
use App\Modules\JurManagerCabinet\Domain\Service\ContractReissueService;

class StartReissueContractUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface          $webActionRepo,
        protected ManagerRepositoryInterface            $managerRepo,
        protected ContractRepositoryInterface           $contractRepo,

        protected ContractReissueService                $contractReissueService,

        protected CreateJurContractUseCase              $createJurContractUseCase,

    ) {}

    public function handle(): Contract
    {
        // 1. Получение настроек для создания нового пользователя
        $settings = $this->contractReissueService->collectSettings($oldContract);

        // 2. Получение нового договора

        $newContract = $this->contractReissueService->find($id);
        $oldContract = $this->contractRepo->find($dto->getContractId());

        // 3. Перенос настроек
        $this->contractReissueService->transferSettings($newContract, $settings);

        // 4. Отключение старого договора со след месяца
        $this->contractRepo->archiveForReissue($oldContract);

        // 5. Активируем статус договора

        // 6. Возвращаем информацию
        return $newContract;
    }
}