<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserPayable;
use App\Modules\Common\Domain\Repository\UserPayableRepository;
use App\Modules\Common\Domain\Repository\UserPayableTypeRepository;
use App\Modules\Common\Domain\Service\Dto\Request\TypedWriteOffDto;

class UserPayableService
{
    public function __construct(
        protected UserPayableRepository $userPayableRepo,
        protected UserPayableTypeRepository $userPayableTypeRepo,
        protected UserServModePriceService $userServModePriceService,
    ) {}

    /**
     * Заглушка: создание записи user_payables.
     * Реальную логику/поля нужно будет доделать вместе с mapping сущностей.
     */
    public function createForAddingService(TypedWriteOffDto $writeOffDto): UserPayable
    {
        $userPayable = new UserPayable();

        //Подготовка данных:
        $payable = $this->userServModePriceService->calculatePayableForUserServMode($writeOffDto->getServMode());
        $amount = $this->userServModePriceService->calculateAmountForUserServMode($writeOffDto->getServMode());
        $influence = $this->userServModePriceService->calculateInfluence();

        // Наполнение
        $userPayable->setPayable($payable);
        $userPayable->setUnits($writeOffDto->getServMode()->getUnits());
        $userPayable->setCreatedAt(new \DateTimeImmutable());
        $userPayable->setDiscount($writeOffDto->getDiscount());
        $userPayable->setFinPeriod($writeOffDto->getServMode()->getFinPeriod());
        $userPayable->setAmount($amount);
        $userPayable->setCost($writeOffDto->getServMode()->getMode()->getProdServModeCost()->getCost());
        $userPayable->setInfluence($influence);
        $userPayable->setFidRefund($writeOffDto->getRefundFinPeriod()?->getId() ?? 0);
        $userPayable->setType($this->userPayableTypeRepo->findOneBy(['code' => $writeOffDto->getPayableType()]));
        $userPayable->setUser($writeOffDto->getUser());
        $userPayable->setUserServMode($writeOffDto->getServMode());
        $userPayable->setServiceMode($writeOffDto->getServMode()->getMode());
        $userPayable->setIsReal($writeOffDto->isReal());
        $userPayable->setIsApplied($writeOffDto->isApplied());

        $userPayable = $this->save($userPayable);

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
