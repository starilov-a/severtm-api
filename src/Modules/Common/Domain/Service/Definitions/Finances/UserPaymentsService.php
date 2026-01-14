<?php

namespace App\Modules\Common\Domain\Service\Definitions\Finances;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\ProdDiscountHistoryRepository;
use App\Modules\Common\Domain\Repository\UserPayableTypeRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Definitions\Finances\Payables\Calculators\NoPacketCalculator;
use App\Modules\Common\Domain\Service\Definitions\Finances\Payables\Calculators\RefundCalculator;
use App\Modules\Common\Domain\Service\Rules\Chains\UserPayable\ShouldMakeUserPayableRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\OnlyFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Definitions\FinPeriod\IsCurrentFinPeriodRule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UserPaymentsService
{
    public function __construct(
        protected ProdDiscountHistoryRepository $writeOffRepo,
        protected UserRepository                $userRepo,
        protected WebActionRepository           $webActionRepo,
        protected UserPayableTypeRepository     $userPayableTypeRepo,

        protected LoggerService                 $loggerService,
        protected UserPayableService            $userPayableService,
        protected ProdDiscountTempService       $prodDiscountTempService,

        protected IsCurrentFinPeriodRule        $isCurrentFinPeriodRule,

        protected NoPacketCalculator            $noPacketCalculator,
        protected RefundCalculator              $refundCalculator,
    ){}

    /**
     * Оплата указанной услуги в текущем фин периоде
     *
     * @param UserServMode $userServMode
     * @return bool
     */
    public function paymentsForServiceMode(UserServMode $userServMode, string $comment = ''): bool
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_MAKE_WRITEOFF');

        //добавить проверку на текущий фин период
        $result = $this->isCurrentFinPeriodRule->check(new OnlyFinPeriod($userServMode->getFinPeriod()));
        if (!$result->ok)
            throw new ImportantBusinessException($master->getId() ?? 0, $webAction->getId() ?? 0, $result->message);

        //создаем платеж
        $userPayable = $this->userPayableService->create($this->noPacketCalculator->calculate($userServMode));

        // создаем задолженность
        $discountTemp = $this->prodDiscountTempService->createForAddingMode($userPayable, $comment);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            559,
            "Списание при подключении режима услуги прошло успешно! 
            uid:{$userServMode->getUser()->getId()},
            usmid:{$userServMode->getId()},
            upid:{$userPayable->getId()},
            dtid:{$discountTemp->getId()}",
            true
        ));

        return true;
    }

    /**
     * Перерасчёт по указанной услуге
     *
     * @param UserServMode $userServMode
     * @return bool
     */
    public function refundForServiceMode(UserServMode $userServMode, string $comment = ''): bool
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_UNFREEZE_ACCOUNT');

        //создаем отрицательный платеж
        $userPayable = $this->userPayableService->create($this->refundCalculator->calculate($userServMode));

        // создаем задолженность
        $discountTemp = $this->prodDiscountTempService->createForAddingMode($userPayable, $comment);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            559,
            "Начисление при перерасчёте услуги прошло успешно! 
            uid:{$userPayable->getUser()->getId()},
            usmid:{$userPayable->getServiceMode()->getId()},
            upid:{$userPayable->getId()},
            dtid:{$discountTemp->getId()}",
            true
        ));

        return true;
    }

    /*
     * Активация автоплатежа
     * */
    public function enableUserAutopayment(User $user): bool
    {
        return false;
    }

    /*
     * Отключение автоплатежа
     * */
    public function disableUserAutopayment(User $user): bool
    {
        return false;
    }
}