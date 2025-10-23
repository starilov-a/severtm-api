<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
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
    public function pay($uid, $cost): array
    {

    }

    public function getBalance(int $uid): array
    {
        $balance = $this->balanceRepo->find($uid);
        if(!$balance){
                throw new BusinessException('Баланс пользователя не обнаружен');
        }

        return [
            'balance' => $balance->get()
        ];
    }

    public function getDebt($uid): array
    {
        $debt = $this->debtRepo->sumByUser($uid);

        return [
            'debt' => $debt
        ];
    }

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

    public function getReplenishments(FilterDto $filter, int $uid): array
    {
        $replenishments = $this->replenishmentRepo->findByUser($filter, $uid);

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

    public function enableAutopayment(int $uid): array
    {
        return [];
    }

    public function disableAutopayment(int $uid): array
    {
        return [];
    }
}
