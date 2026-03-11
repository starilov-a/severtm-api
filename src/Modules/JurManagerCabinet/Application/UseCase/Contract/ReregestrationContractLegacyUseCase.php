<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Contract;


use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\ReregestrationContractLegacyDto;
use App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Contract\ReregestractionContext;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanReregestrationContractRuleChain;
use App\Modules\JurManagerCabinet\Domain\Service\ContractReissueService;

class ReregestrationContractLegacyUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface          $webActionRepo,
        protected ManagerRepositoryInterface            $managerRepo,
        protected ContractRepositoryInterface           $contractRepo,

        protected ContractReissueService                $contractReissueService,

        protected CreateJurContractUseCase              $createJurContractUseCase,

        protected CanReregestrationContractRuleChain    $canReregestrationContractRuleChain,
    ) {}

    public function handle(ReregestrationContractLegacyDto $dto): Contract
    {
        $manager = $this->managerRepo->find($dto->getManagerId());
        $oldContract = $this->contractRepo->find($dto->getContractId());

        // 1. Бизнес проверки
        $result = $this->canReregestrationContractRuleChain->checkAll(
            new ReregestractionContext(
                $dto->getNewInn(),
                $oldContract->getInn()
            )
        );
        if (!$result->ok) {
            //TODO: поправить тип исключения
            throw new \DomainException($result->message);
        }

        // 2. Получение существуюих настроек
        $settings = $this->contractReissueService->collectSettings($oldContract);

        // 3. Создание нового договора через BuildermanCabinet
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

        //4. Перенос данных со старого договора на новый
        $this->contractReissueService->transferSettings($newContract, $settings);

        //5. Отключение старого договора
        $this->contractRepo->archiveForReissue($oldContract);

        return $newContract;
    }
}