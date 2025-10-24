<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Repository\BalanceRepository;
use App\Modules\UserCabinet\Repository\DebtRepository;
use App\Modules\UserCabinet\Repository\ReplenishmentRepository;
use App\Modules\UserCabinet\Repository\WriteOffRepository;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffCollection;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffDto;

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
        if(!$balance){
                throw new BusinessException('Баланс пользователя не обнаружен');
        }

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

    public function getWriteOffs(FilterDto $filter, int $uid): WriteOffCollection
    {
        $writeOffs = $this->writeOffRepo->findByUser($filter, $uid);

        $dtoCollection = new WriteOffCollection();

        foreach ($writeOffs as $writeOff) {
            $dtoCollection->add(new WriteOffDto($writeOff));
        }
        return $dtoCollection;
    }

    /*
    * Пополнения пользователя
    * */
    public function getReplenishments(FilterDto $filter, int $uid): ReplenishmentsCollectionDto
    {
        $replenishments = $this->replenishmentRepo->findByUser($filter, $uid);
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
