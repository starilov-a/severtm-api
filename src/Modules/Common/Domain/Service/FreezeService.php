<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\Dto\Response\FreezeUserStatusDto;
use App\Modules\Common\Domain\Service\Rules\Chains\Freeze\CanFreezeUserRuleChain;
use App\Modules\Common\Domain\Service\Rules\Chains\Freeze\CanUnfreezeUserRuleChain;
use App\Modules\Common\Domain\Service\Rules\Chains\Freeze\CreateFreezeTaskRuleChain;
use App\Modules\Common\Domain\Service\Rules\Chains\Freeze\UnfreezeUserChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Contexts\UserContext;
use App\Modules\Common\Domain\Service\Rules\Definitions\Semaphore\CloseMonthSemaphoreIsNotRunningRule;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;

class FreezeService
{
    protected const GET_FREEZE_ACTION_CID = 'WA_FREEZE_INFO';
    public function __construct(
        protected UserRepository $userRepo,
        protected FreezeReasonRepository $freezeReasonRepo,
        protected WebActionRepository $webActionRepo,

        protected CanFreezeUserRuleChain $canFreezeUserRuleChain,
        protected CanUnfreezeUserRuleChain $canUnfreezeUserRuleChain,
    ) {}

    public function getClientReasonForFreeze(): array
    {
        return $this->freezeReasonRepo->findBy(['isAdmin' => false]);
    }

    public function getUserFreezeStatus(User $user): FreezeUserStatusDto
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid(self::GET_FREEZE_ACTION_CID);


        // 1. делаем цепочку проверок, чтобы понять, можно ли замораживать
        $freezeUserRuleResult = $this->canFreezeUserRuleChain->checkAllWithResult(
            new UserContext($webAction, $master, $user)
        );

        // 2. делаем цепочку проверок, чтобы понять, можно ли размораживать
        $unfreezeUserRuleResult = $this->canUnfreezeUserRuleChain->checkAnyWithResult(
            new UserContext($webAction, $master, $user)
        );

        return new FreezeUserStatusDto(
            $freezeUserRuleResult->ok,
            $unfreezeUserRuleResult->ok,
            $user->getBlockState()->getCode() === 'frozen',
            $freezeUserRuleResult->message,
            $unfreezeUserRuleResult->message
        );
    }
}
