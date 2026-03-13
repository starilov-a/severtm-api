<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Contexts\Definitions\Freeze\CreateFreezeTaskContext;
use App\Modules\UserCabinet\Domain\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Freeze\CreateFreezeTaskRuleChain;
use App\Modules\UserCabinet\Domain\Service\TaskService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class CreateTaskOnFreezeUseCase
{
    public function __construct(
        protected LoggerService           $loggerService,
        protected TaskService             $taskService,

        protected UserTaskStateRepositoryInterface $taskStateRepo,
        protected UserTaskTypeRepositoryInterface  $taskTypeRepo,
        protected UserRepositoryInterface          $userRepo,
        protected WebActionRepositoryInterface     $webActionRepo,

        protected CreateFreezeTaskRuleChain $createFreezeTaskRuleChain,

    ) {}

    public function handle(CreateUserTaskDto $createUserTaskDto): UserTask
    {
        /* @var User $master */
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