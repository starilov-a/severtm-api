<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\Freeze\CreateFreezeTaskContext;
use App\Modules\UserCabinet\Domain\Entity\UserTask;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\UserTaskStateRepository;
use App\Modules\UserCabinet\Domain\Repository\UserTaskTypeRepository;
use App\Modules\UserCabinet\Domain\Repository\WebActionRepository;
use App\Modules\UserCabinet\Domain\Rules\Chains\Freeze\CreateFreezeTaskRuleChain;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\UserCabinet\Domain\Service\TaskService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class CreateTaskOnFreezeUseCase
{
    public function __construct(
        protected LoggerService           $loggerService,
        protected TaskService             $taskService,

        protected UserTaskStateRepository $taskStateRepo,
        protected UserTaskTypeRepository  $taskTypeRepo,
        protected UserRepository          $userRepo,
        protected WebActionRepository     $webActionRepo,

        protected CreateFreezeTaskRuleChain $createFreezeTaskRuleChain,

    ) {}

    public function handle(CreateUserTaskDto $createUserTaskDto): UserTask
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('W3_FIRST_FREEZE_ACCOUNT');

        // Бизнес логика
        $this->createFreezeTaskRuleChain->checkAll(
            new CreateFreezeTaskContext(
                $webAction,
                $master,
                $createUserTaskDto->getUser(),
                $createUserTaskDto->getStartDate()
            )
        );

        $createUserTaskDto->setUserTaskState($this->taskStateRepo->findOneBy(['code' => 'new']));
        $createUserTaskDto->setUserTaskType($this->taskTypeRepo->findOneBy(['code' => 'freeze']));

        $freezeTask = $this->taskService->createUserTask($createUserTaskDto);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Задача на заморозку пользователя успешно создана!',
            true
        ));

        return $freezeTask;

    }
}