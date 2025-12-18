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
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class FreezeService
{
    protected const FREEZE_ACTION_CID = 'W3_FIRST_FREEZE_ACCOUNT';
    protected const UNFREEZE_ACTION_CID = 'WA_UNFREEZE_ACCOUNT';

    public function __construct(
        protected EntityManagerInterface $em,
        protected LoggerService $loggerService,

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
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid(self::FREEZE_ACTION_CID);

        // Бизнес логика
        $this->createFreezeTaskRuleChain->checkAll(
            new CreateFreezeTaskContext(
                $webAction,
                $master,
                $createUserTaskDto->getUser(),
                $createUserTaskDto->getStartDate()
            )
        );

        return $this->em->getConnection()->transactional(function () use (
            $webAction,
            $master,
            $createUserTaskDto,
        ) {
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
        });
    }

    public function createUnfreezeUserTask(UserTask $userTask): UserTask
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid(self::UNFREEZE_ACTION_CID);

        $userTask->setState($this->taskStateRepo->findOneBy(['code' => 'cancelled']));
        $this->taskService->update($userTask);

        return $this->em->getConnection()->transactional(function () use (
            $webAction,
            $master,
            $userTask,
        ) {
            $userTask->setState($this->taskStateRepo->findOneBy(['code' => 'cancelled']));
            $userTask = $this->taskService->update($userTask);

            $this->loggerService->businessLog(new BusinessLogDto(
                $master->getId(),
                $webAction->getId(),
                'Задача на разморозку пользователя'.$userTask->getUser()->getId().' успешно создана!',
                true
            ));

            return $userTask;
        });
    }

    public function getClientReasonForFreeze(): array
    {
        return $this->freezeReasonRepo->findBy(['isAdmin' => false]);
    }
}
