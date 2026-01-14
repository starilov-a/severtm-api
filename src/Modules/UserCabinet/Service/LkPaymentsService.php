<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\BalanceService;
use App\Modules\Common\Domain\Service\DebtService;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\ReplenishmentService;
use App\Modules\Common\Domain\Service\UserPaymentsService;
use App\Modules\Common\Domain\Service\WriteOffService;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Service\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffCollectionDto;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffDto;

class LkPaymentsService
{
    const PAYMENT_LINK_BY_DISTRICT = [
        '1001' => 'https://novtele.ru/oplata/',
        '1013' => 'https://chetelecom.ru/oplata/',
        '1022' => 'https://izet.ru/oplata/',
        '1023' => 'https://izet.ru/oplata/',
        '1024' => 'https://izet.ru/oplata/',
        '1025' => 'https://izet.ru/oplata/',
        '1026' => 'https://izet.ru/oplata/',
        '1050' => 'https://yartele.com/oplata/',
        '1051' => 'https://yartele.com/oplata/',
        '1052' => 'https://yartele.com/oplata/',
        '1053' => 'https://izet.ru/oplata/',
    ];

    public function __construct(
        protected BalanceService          $balanceSerivce,
        protected WriteOffService         $writeOffService,
        protected ReplenishmentService    $replenishmentService,
        protected ReplenishmentRepository $replenishmentRepository,
        protected DebtService             $debtService,
        protected UserPaymentsService     $userPaymentsService,
        protected UserRepository          $userRepo,
        protected WriteOffRepository      $writeOffRepo,
    )
    {
    }

    /*
     * Оплата услуг
     * */
    public function getPaymentLink($district_id): string
    {
        if (!isset(self::PAYMENT_LINK_BY_DISTRICT[$district_id])) {
            throw new BusinessException('Регион пользователя не определен');
        }
        return self::PAYMENT_LINK_BY_DISTRICT[$district_id];
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
        $writeOffs = $this->writeOffRepo->findBy(
            ['user' => $this->userRepo->find($uid)],
            ['discountDateTs' => 'DESC'],
            $filter->getLimit(),
            $filter->getOffset()
        );

        $dtoCollection = new WriteOffCollectionDto();
        foreach ($writeOffs as $writeOff)
            $dtoCollection->add(new WriteOffDto($writeOff));

        return $dtoCollection;
    }

    /*
     * Пополнения пользователя
     * */
    public function getReplenishments(int $uid, FilterDto $filter): ReplenishmentsCollectionDto
    {
        $replenishments = $this->replenishmentRepository->findBy(
            ['user' => $this->userRepo->find($uid)],
            ['dateTs' => 'DESC'],
            $filter->getLimit(),
            $filter->getOffset()
        );

        $dtoCollection = new ReplenishmentsCollectionDto();
        foreach ($replenishments as $replenishment)
            $dtoCollection->add(new ReplenishmentDto($replenishment));

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
