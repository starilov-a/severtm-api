<?php

namespace App\Modules\Common\Domain\Service\Definitions\Finances;

use App\Modules\Common\Domain\Entity\UserPayable;
use App\Modules\Common\Domain\Repository\UserPayableRepository;
use App\Modules\Common\Domain\Repository\UserPayableTypeRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Definitions\Finances\Payables\CalculatedPayable;
use App\Modules\Common\Domain\Service\Rules\Chains\UserPayable\ShouldMakeUserPayableRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\ShouldCreateUserPayableContext;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class UserPayableService
{
    public function __construct(
        protected UserRepository                 $userRepo,
        protected WebActionRepository            $webActionRepo,
        protected UserPayableRepository          $userPayableRepo,
        protected UserPayableTypeRepository      $userPayableTypeRepo,

        protected UserPayableParameterService    $userPayableParameterService,

        protected ShouldMakeUserPayableRuleChain $shouldMakeUserPayableRuleChain,
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

        $userPayable = $this->save($userPayable);

        // Наполнение доп. параметров
        if (!empty($calculatedPayable->getDevice()))
            $this->userPayableParameterService->addLinkToDevice($userPayable, $calculatedPayable->getDevice());

        return $userPayable;
    }

    protected function save(UserPayable $userPayable): UserPayable
    {
        $em = $this->userPayableRepo->getEntityManager();
        $em->persist($userPayable);
        $em->flush();

        return $userPayable;
    }
}
