<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Reissue;

use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Queue\ScheduleReissueTaskDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Queue\ScheduleTaskDto;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;
use App\Modules\JurManagerCabinet\Application\UseCase\Contract\CreateJurContractUseCase;
use App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Reissue\ReissueContext;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\TaskState;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\TaskType;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractReissueProcessRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractStatusRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ManagerRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\TaskSchedulerInterface;
use App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract\CanReissueContractRuleChain;

class ScheduleReissueContractUseCase
{
    public function __construct(
        protected ManagerRepositoryInterface                $managerRepo,
        protected ContractRepositoryInterface               $contractRepo,
        protected ContractStatusRepositoryInterface         $contractStatusRepo,
        protected ContractReissueProcessRepositoryInterface $contractReissueProcessRepo,
        protected TaskSchedulerInterface                    $taskSchedulerRepo,

        protected CanReissueContractRuleChain               $canReissueContractRuleChain,

        protected CreateJurContractUseCase                  $createJurContractUseCase,
    ) {}

    public function execute(ReissueContractDto $dto): ContractReissueProcess
    {
        //TODO: добавить проверку наличия договоров и менеджеров в репо
        $oldContract = $this->contractRepo->find($dto->getContractId());

        // 1. Бизнес проверки
        $result = $this->canReissueContractRuleChain->checkAll(new ReissueContext(
            $dto->getNewInn(),
            $oldContract->getInn(),
            $dto->getDateReissue(),
            $oldContract
        ));

        // 2. Обработка бизнеса ошибок
        if (!$result->ok) {
            throw new \DomainException($result->message);
        }

        // 2. Создание нового договора
        $newContract = $this->createJurContractUseCase->execute(new CreateJurContractDto(
                (string)$dto->getNewInn(),
                $dto->getFio(),
                $dto->getLogin(),
                $dto->getPassword(),
                $dto->getPhone(),
        ));

        // 3. Выставляем статус "На переоформлении"
        $this->contractStatusRepo->changeContractStatus($newContract, ContractStatus::ON_REISSUED);

        // 4. Заведение задачи на переоформление
       $task = $this->taskSchedulerRepo->scheduleForReissue(new ScheduleReissueTaskDto(
            TaskType::CONTRACT_REISSUE,
            TaskState::NEW,
            $dto->getContractId(),
            $dto->getManagerId(),
            new \DateTimeImmutable(),
            $dto->getDateReissue(),
            $dto->getComment(),
            $dto
        ));

        // 5. Наполняем сущность процесса переоформления
        $contractReissueProcess = new ContractReissueProcess(
            oldContractId: $oldContract->getId(),
            newContractId: $newContract->getId(),
            contractReissueDto: $dto,
            scheduleAt: $dto->getDateReissue(),
            taskId: $task->id()
        );

        return $contractReissueProcess;
    }
}