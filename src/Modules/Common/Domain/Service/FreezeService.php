<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\FreezeReason;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Entity\UserTaskType;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskRuleChain;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class FreezeService
{
    private const FREEZE_ACTION_CID = 'WA_USERS_FREEZE';

    public function __construct(
        protected TaskService $taskService,
        protected UserTaskStateRepository $taskStateRepo,
        protected UserTaskTypeRepository $taskTypeRepo,
        protected UserRepository $userRepo,
        protected FreezeReasonRepository $freezeReasonRepo,
        protected WebActionRepository $webActionRepo,
        protected FinPeriodRepository $finPeriodRepo,
        protected CreateFreezeTaskRuleChain $createFreezeTaskRuleChain,
    ) {
    }

    public function createFreezeUserTask(CreateUserTaskDto $createUserTaskDto): UserTask
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $freezeType = $this->requireTaskType('freeze');
        $newState = $this->requireTaskState('new');
        $finishedState = $this->requireTaskState('finished');

        $webAction = $this->webActionRepo->findIdByCid(self::FREEZE_ACTION_CID);
        $actionId = $webAction?->getId() ?? 0;

        $context = new CreateFreezeTaskContext(
            user: $createUserTaskDto->getUser(),
            master: $master,
            actionId: $actionId,
            startDate: $createUserTaskDto->getStartDate(),
            freezeTaskType: $freezeType,
            newState: $newState,
            finishedState: $finishedState,
            currentFinPeriod: $this->finPeriodRepo->getCurrent(),
            defaultTariffCost: $this->resolveDefaultTariffCost($createUserTaskDto->getUser()),
            userBill: $createUserTaskDto->getUser()->getBill(),
            registrationDate: $this->resolveRegistrationDate($createUserTaskDto->getUser()),
            now: new \DateTimeImmutable(),
        );

        $this->createFreezeTaskRuleChain->checkAll($context);

        $createUserTaskDto->setUserTaskState($newState);
        $createUserTaskDto->setUserTaskType($freezeType);

        return $this->taskService->createUserTask($createUserTaskDto);
    }

    /**
     * @return FreezeReason[]
     */
    public function getClientReasonForFreeze(): array
    {
        return $this->freezeReasonRepo->findBy(['isAdmin' => false]);
    }

    private function requireTaskType(string $code): UserTaskType
    {
        $type = $this->taskTypeRepo->findOneBy(['str_code' => $code]);
        if (!$type) {
            throw new \RuntimeException(sprintf('User task type "%s" not found', $code));
        }

        return $type;
    }

    private function requireTaskState(string $code): UserTaskState
    {
        $state = $this->taskStateRepo->findOneBy(['str_code' => $code]);
        if (!$state) {
            throw new \RuntimeException(sprintf('User task state "%s" not found', $code));
        }

        return $state;
    }

    private function resolveDefaultTariffCost(User $user): float
    {
        $tariff = $user->getCurrentTariff();
        if (null === $tariff) {
            return 0.0;
        }

        return (float)$tariff->getPrice();
    }

    private function resolveRegistrationDate(User $user): ?\DateTimeImmutable
    {
        $regDate = $user->getRegDate();
        if ($regDate <= 0) {
            return null;
        }

        return \DateTimeImmutable::createFromFormat('U', (string)$regDate) ?: null;
    }
}
