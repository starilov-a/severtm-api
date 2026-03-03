<?php

namespace App\Modules\UserCabinet\Application;

use App\Modules\Common\Domain\Repository\ProdDiscountHistoryRepository;
use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Rules\Chains\Break\ClientCanGetBreakRuleChain;
use App\Modules\Common\Domain\Service\BreakService;
use App\Modules\Common\Domain\Service\Definitions\Finances\BalanceService;
use App\Modules\Common\Domain\Service\Definitions\Finances\DebtService;
use App\Modules\Common\Domain\Service\Definitions\Finances\ProdDiscountHistoryService;
use App\Modules\Common\Domain\Service\Definitions\Finances\ReplenishmentService;
use App\Modules\Common\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Application\Dto\Response\WriteOffCollectionDto;
use App\Modules\UserCabinet\Application\Dto\Response\WriteOffDto;
use App\Modules\UserCabinet\Application\UseCase\Break\TakeBreakForOneDayUseCase;
use App\Modules\UserCabinet\Infrastructure\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

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
        protected EntityManagerInterface            $em,
        protected BalanceService                    $balanceService,
        protected ProdDiscountHistoryService        $writeOffService,
        protected ReplenishmentService              $replenishmentService,

        protected DebtService                       $debtService,
        protected UserPaymentsService               $userPaymentsService,
        protected BreakService                      $breakService,

        protected ReplenishmentRepository           $replenishmentRepo,
        protected UserRepository                    $userRepo,
        protected ProdDiscountHistoryRepository     $writeOffRepo,
        protected WebActionRepository               $webActionRepo,

        protected ClientCanGetBreakRuleChain        $userCanGetBreakRuleChain,

        protected TakeBreakForOneDayUseCase         $userCanTakeBreakForOneDayUseCase,
    ) {}

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
        $balance = $this->balanceService->getUserBalance($user);
        $debt = $this->debtService->getUserDebt($user);

        return [
            'balance' => $balance->get() - $debt
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
        $replenishments = $this->replenishmentRepo->findBy(
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

    /*
     * Получение отсрочки для клиента
     * */
    public function takeBreak(int $uid): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
        ) {
            $user = $this->userRepo->find($uid);

            $this->userCanTakeBreakForOneDayUseCase->handle($user);

            return true;
        });
    }

    /*
     * Получение информации об отсрочках
     * */
    public function canTakeBreak(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        // проверка для клиента
        $data = $this->breakService->getBreakStatusForUser($user);

        $breakStatus = [
            'isAvailable' => $data['isAvailable'],
            'isActive' => $data['isActive'],
            'count' => $data['countAvailableBreaks'],
        ];

        if ($data['isActive'])
            $breakStatus['endDate'] = $data['deadlineDate']->format('Y-m-d');

        return $breakStatus;
    }
}
