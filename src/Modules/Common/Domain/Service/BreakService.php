<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\BlockState;
use App\Modules\Common\Domain\Entity\CreditHistory;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Repository\ConfigRepository;
use App\Modules\Common\Domain\Repository\CreditHistoryRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreditHistoryLogDto;
use App\Modules\Common\Domain\Service\Rules\Chains\Break\CanGetBreakRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\OnlyUserContext;
use App\Modules\Common\Domain\Service\Rules\Contexts\UserContext;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class BreakService
{
    public function __construct(
        protected LoggerService             $loggerService,
        protected UserService               $userService,
        protected CreditHistoryService      $creditHistoryService,

        protected WebActionRepository       $webActionRepo,
        protected ConfigRepository          $configRepo,
        protected UserRepository            $userRepo,
        protected CreditHistoryRepository   $creditHistoryRepo,
        protected BlockStateRepository      $blockStateRepo,

        protected CanGetBreakRuleChain      $canGetBreakRuleChain,
    ) {}

    public function takeBreakForUser(User $user, int $countDays): CreditHistory
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_GIVECREDIT');

        // 1. Бизнес-правила
        $this->canGetBreakRuleChain->checkAll(new UserContext($webAction, $master, $user));

        // 2. Изменение даты deadline
        if ($user->getBlockState() == $this->blockStateRepo->findByCode('blocked'))
            $user->setBlockDate(new \DateTimeImmutable());
        $user->setBlockState($this->blockStateRepo->findByCode('unblocked'));
        $user->setCredit(true);

        $now = new \DateTimeImmutable();
        $deadline = $now->modify('+' . $countDays . ' days');
        $user->setCreditDeadline($deadline);

        $this->userService->save($user);

        // 3. добавление истории отсрочки
        $creditHistoryLogDto = new CreditHistoryLogDto($deadline, $user, $master, $user->getBill());
        $creditHistoryLog = $this->creditHistoryService->createCreditHistoryLog($creditHistoryLogDto);

        return $creditHistoryLog;
    }

    public function getBreakStatusForUser(User $user): array
    {
        $isAvailable = $this->canGetBreakRuleChain->checkAllWithResult(new OnlyUserContext($user))->ok;
        $countAvailableBreaks = $this->countAvailableBreaksForUser($user);

        return [
            'isAvailable' => $isAvailable,
            'countAvailableBreaks' => $countAvailableBreaks,
        ];
    }

    public function countAvailableBreaksForUser(User $user): int
    {
        $totalCountBreaks = $this->configRepo->findOneBy(['cid' => 'AdditionalCreditTimes']);
        $totalCountUsedBreaks = $this->creditHistoryRepo->findBy(['user' => $user]);

        return ($totalCountBreaks - $totalCountUsedBreaks);
    }
}