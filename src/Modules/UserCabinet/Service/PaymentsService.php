<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Repository\BalanceRepository;
use App\Modules\UserCabinet\Repository\DebtRepository;
use App\Modules\UserCabinet\Repository\ReplenishmentRepository;
use App\Modules\UserCabinet\Repository\WriteOffRepository;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;

class PaymentsService
{
    protected BalanceRepository $balanceRepo;
    protected WriteOffRepository $writeOffRepo;
    protected ReplenishmentRepository $replenishmentRepo;
    protected DebtRepository $debtRepo;
    public function __construct(
        BalanceRepository $balanceRepo,
        WriteOffRepository $writeOffRepo,
        ReplenishmentRepository $replenishmentRepo,
        DebtRepository $debtRepo
    )
    {
        $this->balanceRepo = $balanceRepo;
        $this->writeOffRepo = $writeOffRepo;
        $this->replenishmentRepo = $replenishmentRepo;
        $this->debtRepo = $debtRepo;
    }

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
        $balance = $this->balanceRepo->find($uid);

        return [
            'balance' => $balance->get()
        ];
    }

    /*
     * Получение задолжности
     * */
    public function getDebt($uid): array
    {
        $debt = $this->debtRepo->sumByUser($uid);

        return [
            'debt' => $debt
        ];
    }

    /*
     * Получение списаний
     * */
    public function getWriteOffs(FilterDto $filter, int $uid): array
    {
        $writeOffs = $this->writeOffRepo->findByUser($filter, $uid);

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
        $replenishments = $this->replenishmentRepo->findByUser($uid, $filter);

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