<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\Calculators;

use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use App\Modules\UserCabinet\Domain\Repository\FinPeriodRepository;
use App\Modules\UserCabinet\Domain\Repository\UserPayableTypeRepository;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\CalculatedPayable;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\PayableCalculatorInterface;

class NoPacketCalculator extends DefaultCalculator implements PayableCalculatorInterface
{
    public function __construct(
        protected UserPayableTypeRepository $userPayableTypeRepo,
        protected FinPeriodRepository $finPeriodRepo,
    ) {}
    public function calculate(UserServMode $userServMode): CalculatedPayable
    {
        $calcPayable = new CalculatedPayable();
        $calcPayable->setServMode($userServMode);
        $calcPayable->setPayableType($this->userPayableTypeRepo->findOneByCode('no_packet'));
        $calcPayable->setUser($userServMode->getUser());
        $calcPayable->setCurrentFinPeriod($this->finPeriodRepo->getCurrent());
        $calcPayable->setUnits($userServMode->getUnits());
        $calcPayable->setCreatedAt(new \DateTimeImmutable());
        $calcPayable->setDevice($userServMode->getDevice());
        $calcPayable->setProdCost($userServMode->getMode()->getProdServModeCost()->getCost());

        $units = $userServMode->getUnits();
        $cost = $userServMode->getMode()->getProdServModeCost()->getCost();
        $amount = $this->calculateAmount($cost, $units);
        $influence = $this->calculateInfluence();
        $payable = $this->calculatePayable($amount, $influence);

        $calcPayable->setAmount($amount);
        $calcPayable->setInfluence($influence);
        $calcPayable->setPayable($payable);

        return $calcPayable;
    }
}