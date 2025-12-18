<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\Dto\Request\TypedWriteOffDto;
use App\Modules\Common\Domain\Service\Rules\Chains\ShouldMakeWriteOffRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\ShouldMakeWriteOffContext;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class WriteOffService
{
    protected const WRITEOFF_ACTION_CID = 'WA_USERS_MAKE_WRITEOFF';
    public function __construct(
        protected WriteOffRepository $writeOffRepo,
        protected ShouldMakeWriteOffRuleChain $shouldMakeWriteOffRuleChain,
        protected UserServModePriceService $userServModePriceService,
        protected UserPayableService $userPayableService,
        protected ProdDiscountTempService $prodDiscountTempService,
        protected UserRepository $userRepository,
        protected WebActionRepository $webActionRepo,

        protected LoggerService $loggerService,
    ){}

    /*
     * Получение списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }

    /*
     * Списание при добавлении режима
     * */
    public function makeWriteOffForAddingMode(TypedWriteOffDto $writeOffDto): void
    {
        $master = $this->userRepository->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid(self::WRITEOFF_ACTION_CID);

        // наполнение контекста для проверки
        $contextForRule = new ShouldMakeWriteOffContext(
            $webAction,
            $master,
            $writeOffDto->getUser(),
            $writeOffDto->getServMode()->getFinPeriod(),
            $writeOffDto->getPayableType(),
            $writeOffDto->getServMode(),
            $writeOffDto->getRefundFinPeriod(),
            $writeOffDto->isApplied(),
            $writeOffDto->isReal()
        );

        // логические проверки
        if (!$this->shouldMakeWriteOffRuleChain->checkAll($contextForRule)) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $master->getId(),
                559,
                'Списание при подключении услуги(usmid:'.$writeOffDto->getServMode()->getId().') не прошло.',
                true
            ));
            return;
        }

        // Создаем платеж
        $userPayable = $this->userPayableService->createForAddingService($writeOffDto);

        // создаем задолженность
        $discountTemp = $this->prodDiscountTempService->createForAddingMode($userPayable, $writeOffDto->getComment());

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            559,
            "Списание при подключении режима услуги прошло успешно! 
            uid:{$writeOffDto->getUser()->getId()},
            usmid:{$writeOffDto->getServMode()->getId()},
            upid:{$userPayable->getId()},
            dtid:{$discountTemp->getId()}",
            true
        ));

    }
}