<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\BalanceRepository;
use App\Modules\Common\Domain\Repository\DebtRepository;
use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\UserPaymentsService;

class LkPaymentsService
{

    public function __construct(
        protected BalanceRepository $balanceRepo,
        protected WriteOffRepository $writeOffRepo,
        protected ReplenishmentRepository $replenishmentRepo,
        protected DebtRepository $debtRepo,
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
        $balance = $this->userPaymentsService->getUserBalance($user);

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
        $debt = $this->userPaymentsService->getUserDebt($user);

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

        $writeOffs = $this->userPaymentsService->getUserWriteOffs($user, $filter);

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

        $replenishments = $this->userPaymentsService->getUserReplenishments($user, $filter);

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