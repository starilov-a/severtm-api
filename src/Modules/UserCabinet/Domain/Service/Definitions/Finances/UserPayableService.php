<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\UserPayable\ShouldCreateUserPayableContext;
use App\Modules\UserCabinet\Domain\Entity\UserPayable;
use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\UserPayable\ShouldMakeUserPayableRuleChain;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\CalculatedPayable;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class UserPayableService
{
    public function __construct(
        protected UserPayableRepositoryInterface          $repo,
        protected UserRepositoryInterface                 $userRepo,
        protected WebActionRepositoryInterface            $webActionRepo,
        protected UserPayableRepositoryInterface          $userPayableRepo,
        protected UserPayableTypeRepositoryInterface      $userPayableTypeRepo,

        protected UserPayableParameterService             $userPayableParameterService,

        protected ShouldMakeUserPayableRuleChain          $shouldMakeUserPayableRuleChain,
    ) {}

    /**
     * Заглушка: создание записи user_payables.
     * Реальную логику/поля нужно будет доделать вместе с mapping сущностей.
     */
    public function create(CalculatedPayable $calculatedPayable): UserPayable
    {

        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_MAKE_WRITEOFF'); //TODO добавить другой экшен

        $this->shouldMakeUserPayableRuleChain->checkAll(
            new ShouldCreateUserPayableContext(
                $webAction,
                $master,
                $calculatedPayable->getUser(),
                $calculatedPayable->getServMode()->getFinPeriod(),
                $calculatedPayable->getPayableType(),
                $calculatedPayable->getServMode(),
                $calculatedPayable->getRefundFinPeriod(),
                $calculatedPayable->isApplied(),
                $calculatedPayable->isReal()
            )
        );

        $userPayable = new UserPayable();

        // Наполнение
        $userPayable->setPayable($calculatedPayable->getPayable());
        $userPayable->setUnits($calculatedPayable->getUnits());
        $userPayable->setCreatedAt(new \DateTimeImmutable());
        $userPayable->setDiscount($calculatedPayable->getDiscount());
        $userPayable->setFinPeriod($calculatedPayable->getCurrentFinPeriod());
        $userPayable->setAmount($calculatedPayable->getAmount());
        $userPayable->setCost($calculatedPayable->getProdCost());
        $userPayable->setInfluence($calculatedPayable->getInfluence());

        //Если refund не определен, тогда ставим 0
        $userPayable->setFidRefund(
            !is_null($calculatedPayable->getRefundFinPeriod()) ?
                $calculatedPayable->getRefundFinPeriod()->getId() :
                0
        );

        $userPayable->setType($calculatedPayable->getPayableType());
        $userPayable->setUser($calculatedPayable->getUser());
        $userPayable->setUserServMode($calculatedPayable->getServMode());
        $userPayable->setServiceMode($calculatedPayable->getServMode()->getMode());
        $userPayable->setIsReal($calculatedPayable->isReal());
        $userPayable->setIsApplied($calculatedPayable->isApplied());

        $userPayable = $this->repo->save($userPayable);

        // Наполнение доп. параметров
        if (!empty($calculatedPayable->getDevice()))
            $this->userPayableParameterService->addLinkToDevice($userPayable, $calculatedPayable->getDevice());

        return $userPayable;
    }
}
