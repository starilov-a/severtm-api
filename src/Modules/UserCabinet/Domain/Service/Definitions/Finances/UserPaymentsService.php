<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\FinPeriod\OnlyFinPeriod;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdDiscountHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Definitions\FinPeriod\IsCurrentFinPeriodRule;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\Calculators\NoPacketCalculator;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\Calculators\RefundCalculator;
use App\Modules\UserCabinet\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

/**
 * Сервис отвечающий за агрегирование работы c задолженностями и начислениями (prod_discount_temp/user_payables)
 *
 */
class UserPaymentsService
{
    public function __construct(
        protected ProdDiscountHistoryRepositoryInterface $writeOffRepo,
        protected UserRepositoryInterface                $userRepo,
        protected WebActionRepositoryInterface           $webActionRepo,
        protected UserPayableTypeRepositoryInterface     $userPayableTypeRepo,

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