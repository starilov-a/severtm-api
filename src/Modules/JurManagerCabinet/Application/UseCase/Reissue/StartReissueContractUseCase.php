<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Reissue;



use App\Modules\JurManagerCabinet\Application\UseCase\Contract\CreateJurContractUseCase;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractStatusRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Service\ContractReissueService;

class StartReissueContractUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface          $webActionRepo,
        protected ManagerRepositoryInterface            $managerRepo,
        protected ContractRepositoryInterface           $contractRepo,
        protected ContractStatusRepositoryInterface     $contractStatusRepo,


        protected ContractReissueService                $contractReissueService,

        protected CreateJurContractUseCase              $createJurContractUseCase,

    ) {}

    public function handle(ContractReissueProcess $contractReissueProcess): Contract
    {
        // 1. Получение договоров
        $oldContract = $this->contractRepo->find($contractReissueProcess->getOldContractId());
        $newContract = $this->contractRepo->find($contractReissueProcess->getNewContractId());

        // 2. Получение настроек, необходимых для переноса
        $settings = $this->contractReissueService->collectSettings($oldContract);

        // 3. Перенос настроек
        $this->contractReissueService->transferSettings($newContract, $settings);

        // 4. Отключение старого договора со след месяца
        $this->contractRepo->archiveForReissue($oldContract);

        // 5. Выставляем статус "Разблокирован"
        $this->contractStatusRepo->changeContractStatus($newContract, ContractStatus::UNBLOCKED);

        // 6. Возвращаем информацию
        return $newContract;
    }
}