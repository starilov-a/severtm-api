<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskRuleChain;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateUnfreezeTaskRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Definitions\Semaphore\CloseMonthSemaphoreIsNotRunningRule;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class FreezeService
{
    protected const FREEZE_ACTION_CID = 'W3_FIRST_FREEZE_ACCOUNT';
    protected const UNFREEZE_ACTION_CID = 'W3_FIRST_UNFREEZE_ACCOUNT';
    protected const GET_FREEZE_ACTION_CID = 'WA_FREEZE_INFO';

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
        protected CloseMonthSemaphoreIsNotRunningRule $closeMonthSemaphoreIsNotRunningRule,
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

        // Бизнес логика
        $ruleResult = $this->closeMonthSemaphoreIsNotRunningRule->check();
        if (!$ruleResult->ok) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $master->getId(),
                $webAction->getId(),
                $ruleResult->message,
                false
            ));
            throw new BusinessException('Ошибка сервера.');
        }


        return $this->em->getConnection()->transactional(function () use (
            $webAction,
            $master,
            $userTask,
        ) {
            $userTask->setState($this->taskStateRepo->findOneBy(['code' => 'cancelled']));
            $userTask = $this->taskService->update($userTask);

            //TODO: тут нужно реализовать процедуру: __sys_unblock_recalc_tm

            $this->loggerService->businessLog(new BusinessLogDto(
                $master->getId(),
                $webAction->getId(),
                'Пользователь'.$userTask->getUser()->getId().' успешно разморожен!.',
                true
            ));

            return $userTask;
        });
    }

    public function getUserFreezeStatus(User $user): array
    {
        $webAction = $this->webActionRepo->findIdByCid(self::GET_FREEZE_ACTION_CID);

        //TODO: много логики

        return $this->taskStateRepo->findBy(['user' => $user, 'type' => $this->taskTypeRepo->findOneBy(['code' => 'freeze'])]);
    }

    public function getClientReasonForFreeze(): array
    {
        return $this->freezeReasonRepo->findBy(['isAdmin' => false]);
    }
}
