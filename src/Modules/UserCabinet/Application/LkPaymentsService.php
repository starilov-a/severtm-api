<?php

namespace App\Modules\UserCabinet\Application;

use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Application\Dto\Response\WriteOffCollectionDto;
use App\Modules\UserCabinet\Application\Dto\Response\WriteOffDto;
use App\Modules\UserCabinet\Application\UseCase\Break\TakeBreakForOneDayUseCase;
use App\Modules\UserCabinet\Domain\Repository\ProdDiscountHistoryRepository;
use App\Modules\UserCabinet\Domain\Repository\ReplenishmentRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\WebActionRepository;
use App\Modules\UserCabinet\Domain\Rules\Chains\Break\ClientCanGetBreakRuleChain;
use App\Modules\UserCabinet\Domain\Service\BreakService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\BalanceService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\DebtService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\ProdDiscountHistoryService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\ReplenishmentService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;
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
}
