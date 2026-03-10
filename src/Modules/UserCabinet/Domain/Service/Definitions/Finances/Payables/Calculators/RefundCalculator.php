<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\Calculators;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\CalculatedPayable;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\PayableCalculatorInterface;

class RefundCalculator extends DefaultCalculator implements PayableCalculatorInterface
{
    public function __construct(
        protected UserPayableTypeRepositoryInterface $userPayableTypeRepo,
        protected FinPeriodRepositoryInterface $finPeriodRepo,
    ) {}
    public function calculate(UserServMode $userServMode): CalculatedPayable
    {
        $calcPayable = new CalculatedPayable();
        $calcPayable->setServMode($userServMode);
        $calcPayable->setPayableType($this->userPayableTypeRepo->findOneByCode('refund'));
        $calcPayable->setUser($userServMode->getUser());
        $calcPayable->setCurrentFinPeriod($this->finPeriodRepo->getCurrent());
        $calcPayable->setRefundFinPeriod($userServMode->getFinPeriod());
        $calcPayable->setUnits($userServMode->getUnits());
        $calcPayable->setCreatedAt(new \DateTimeImmutable());
        $calcPayable->setDevice($userServMode->getDevice());
        $calcPayable->setProdCost($userServMode->getMode()->getProdServModeCost()->getCost());

        $units = $userServMode->getUnits();
        $cost = $userServMode->getMode()->getProdServModeCost()->getCost();
        $amount = $this->calculateAmount($cost, $units);
        // Указываем дату блокировки
        // TODO доработать для полных месяцев другой перерасчет
        $influence = $this->calculateInfluence($userServMode->getUser()->getBlockDate());
        $payable = -1 * $this->calculatePayable($amount, $influence);

        $calcPayable->setAmount($amount);
        $calcPayable->setInfluence($influence);
        $calcPayable->setPayable($payable);

        return $calcPayable;
    }
}