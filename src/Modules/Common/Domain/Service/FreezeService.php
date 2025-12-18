<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\CreateFreezeTaskContext;

class FreezeService
{
    protected const FREEZE_ACTION_CID = 'W3_FIRST_FREEZE_ACCOUNT';

    public function __construct(
        protected TaskService $taskService,
        protected UserTaskStateRepository $taskStateRepo,
        protected UserTaskTypeRepository $taskTypeRepo,
        protected UserRepository $userRepo,
        protected FreezeReasonRepository $freezeReasonRepo,
        protected WebActionRepository $webActionRepo,
        protected FinPeriodRepository $finPeriodRepo,
        protected CreateFreezeTaskRuleChain $createFreezeTaskRuleChain,
    ) {}

    public function createFreezeUserTask(CreateUserTaskDto $createUserTaskDto): UserTask
    {
        // Бизнес логика
        $this->createFreezeTaskRuleChain->checkAll(
            new CreateFreezeTaskContext(
                $this->webActionRepo->findIdByCid(self::FREEZE_ACTION_CID),
                $createUserTaskDto->getUser(),
                $createUserTaskDto->getStartDate()
            )
        );

        // наполнение необходимых типов и состояний задачи
        $createUserTaskDto->setUserTaskState($this->taskStateRepo->findOneBy(['code' => 'new']));
        $createUserTaskDto->setUserTaskType($this->taskTypeRepo->findOneBy(['code' => 'freeze']));

        return $this->taskService->createUserTask($createUserTaskDto);
    }

    public function getClientReasonForFreeze(): array
    {
        return $this->freezeReasonRepo->findBy(['isAdmin' => false]);
    }
}
