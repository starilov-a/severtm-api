<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Service\BalanceService;
use App\Modules\Common\Domain\Service\DebtService;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\ReplenishmentService;
use App\Modules\Common\Domain\Service\UserPaymentsService;
use App\Modules\Common\Domain\Service\WriteOffService;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffCollectionDto;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffDto;

class LkPaymentsService
{

    public function __construct(
        protected BalanceService $balanceSerivce,
        protected WriteOffService $writeOffService,
        protected ReplenishmentService $replenishmentService,
        protected DebtService $debtService,
        protected UserPaymentsService $userPaymentsService,
        protected UserRepository $userRepo
    ){}

    /*
     * Оплата услуг
     * */
    public function pay($uid, $cost): bool
    {
        return false;
    }

    /*
     * Получение баланса
     * */
    public function getBalance(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $balance = $this->balanceSerivce->getUserBalance($user);

        return [
            'balance' => $balance->get()
        ];
    }

    /*
     * Получение задолжности
     * */
    public function getDebt($uid): array
    {
        $user = $this->userRepo->find($uid);
        $debt = $this->debtService->getUserDebt($user);

        return [
            'debt' => $debt
        ];
    }

    /*
     * Получение списаний
     * */
    public function getWriteOffs(int $uid, FilterDto $filter): WriteOffCollectionDto
    {
        $user = $this->userRepo->find($uid);

        $writeOffs = $this->writeOffService->getUserWriteOffs($user, $filter);

        $dtoCollection = new WriteOffCollectionDto();
        foreach ($writeOffs as $writeOff) {
            $dtoCollection->add(new WriteOffDto($writeOff));
        }
        return $dtoCollection;
    }

    /*
     * Пополнения пользователя
     * */
    public function getReplenishments(int $uid, FilterDto $filter): ReplenishmentsCollectionDto
    {
        $user = $this->userRepo->find($uid);

        $replenishments = $this->replenishmentService->getUserReplenishments($user, $filter);

        $dtoCollection = new ReplenishmentsCollectionDto();
        foreach ($replenishments as $replenishment) {
            $dtoCollection->add(new ReplenishmentDto($replenishment));
        }

        return $dtoCollection;
    }

    /*
     * Активация автоплатежа
     * */
    public function enableAutopayment(int $uid): bool
    {
        return false;
    }

    /*
     * Отключение автоплатежа
     * */
    public function disableAutopayment(int $uid): bool
    {
        return false;
    }
}
