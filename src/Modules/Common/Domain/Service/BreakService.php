<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Contexts\Definitions\Break\BreakContext;
use App\Modules\Common\Domain\Contexts\Definitions\Break\OnlyBreakContext;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Repository\ConfigRepository;
use App\Modules\Common\Domain\Repository\CreditHistoryRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Rules\Chains\Break\CanGetBreakRuleChain;
use App\Modules\Common\Domain\Service\Dto\Request\CreditHistoryLogDto;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
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

    public function takeBreakForUser(User $user, int $countDays): bool
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_GIVECREDIT');

        // 1. Бизнес-правила
        $countAvailableBreaks = $this->countAvailableBreaksForUser($user);

        $this->canGetBreakRuleChain->checkAll(new BreakContext($webAction, $master, $user, $countAvailableBreaks));

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
        $this->creditHistoryService->createCreditHistoryLog($creditHistoryLogDto);

        return true;
    }

    public function getBreakStatusForUser(User $user): array
    {
        $countAvailableBreaks = $this->countAvailableBreaksForUser($user);
        $isAvailable = $this->canGetBreakRuleChain->checkAllWithResult(new OnlyBreakContext($user, $countAvailableBreaks))->ok;
        $isActive = $user->isCredit();

        $statusData = [
            'isAvailable' => $isAvailable,
            'isActive' => $isActive,
            'countAvailableBreaks' => $countAvailableBreaks,
        ];

        if ($isActive)
            $statusData['endDate'] = $user->getCreditDeadline();

        return $statusData;
    }

    public function countAvailableBreaksForUser(User $user): int
    {
        $totalCountBreaks = (int)($this->configRepo->findOneBy(['cid' => 'StandardCreditTimes'])?->getValue() ?? 0);
        $totalCountUsedBreaks = $this->creditHistoryRepo->countByUser($user);

        return max(0, $totalCountBreaks - $totalCountUsedBreaks);
    }
}
