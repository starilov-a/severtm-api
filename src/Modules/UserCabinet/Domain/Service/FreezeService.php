<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\User\UserContext;
use App\Modules\UserCabinet\Domain\Dto\Response\FreezeUserStatusDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FreezeReasonRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Freeze\CanFreezeUserRuleChain;
use App\Modules\UserCabinet\Domain\Rules\Chains\Freeze\CanUnfreezeUserRuleChain;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class FreezeService
{
    protected const GET_FREEZE_ACTION_CID = 'WA_FREEZE_INFO';
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected FreezeReasonRepositoryInterface $freezeReasonRepo,
        protected WebActionRepositoryInterface $webActionRepo,

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
            $user->getBlockState()->getCode() === 'frozen',
            $freezeUserRuleResult->ok,
            $unfreezeUserRuleResult->ok,
            $freezeUserRuleResult->message,
            $unfreezeUserRuleResult->message
        );
    }
}
