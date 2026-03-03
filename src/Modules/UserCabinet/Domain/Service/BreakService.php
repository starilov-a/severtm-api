<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\Break\BreakContext;
use App\Modules\UserCabinet\Domain\Contexts\Definitions\Break\OnlyBreakContext;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ConfigRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\CreditHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Break\CanGetBreakRuleChain;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreditHistoryLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class BreakService
{
    public function __construct(
        protected LoggerService             $loggerService,
        protected UserService               $userService,
        protected CreditHistoryService      $creditHistoryService,

        protected WebActionRepositoryInterface       $webActionRepo,
        protected ConfigRepositoryInterface          $configRepo,
        protected UserRepositoryInterface            $userRepo,
        protected CreditHistoryRepositoryInterface   $creditHistoryRepo,
        protected BlockStateRepositoryInterface      $blockStateRepo,

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

        $this->userRepo->save($user);

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

        return [
            'isAvailable' => $isAvailable,
            'isActive' => $isActive,
            'countAvailableBreaks' => $countAvailableBreaks,
            'deadlineDate' => $user->getCreditDeadline()
        ];
    }

    public function countAvailableBreaksForUser(User $user): int
    {
        $totalCountBreaks = (int)($this->configRepo->findOneBy(['cid' => 'StandardCreditTimes'])?->getValue() ?? 0);
        $totalCountUsedBreaks = $this->creditHistoryRepo->countByUser($user);

        return max(0, $totalCountBreaks - $totalCountUsedBreaks);
    }
}
