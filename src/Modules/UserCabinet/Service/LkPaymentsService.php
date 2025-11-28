<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Service\BalanceService;
use App\Modules\Common\Domain\Service\DebtService;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\ReplenishmentService;
use App\Modules\Common\Domain\Service\UserPaymentsService;
use App\Modules\Common\Domain\Service\WriteOffService;

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
    public function getWriteOffs(int $uid, FilterDto $filter): array
    {
        $user = $this->userRepo->find($uid);

        $writeOffs = $this->writeOffService->getUserWriteOffs($user, $filter);

        return array_map(function ($writeOff) {
            return [
                'id' => $writeOff->getId(),
                'payableId' =>$writeOff->getPayableId(),
                'date' => $writeOff->getChargedAt()->format('Y-m-d H:i:s')
            ];
        }, $writeOffs);
    }

    /*
     * Пополнения пользователя
     * */
    public function getReplenishments(int $uid, FilterDto $filter): array
    {
        $user = $this->userRepo->find($uid);

        $replenishments = $this->replenishmentService->getUserReplenishments($user, $filter);

        return array_map(function ($replenishment) {
            return [
                'id' => $replenishment->getId(),
                'login' =>$replenishment->getLogin(),
                'additionalInformation' => $replenishment->getWho(),
                'paymentType' => $replenishment->getWho(),
                'comment' => $replenishment->getComments(),
                'amount' => $replenishment->getAmount(),
                'date' => date("Y-m-d H:i:s", $replenishment->getDateTs())
            ];
        }, $replenishments);
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