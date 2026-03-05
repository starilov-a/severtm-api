<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffBannedAddressRepositoryInterface;

class TariffAllowedForRegionNoBannedRule extends Rule
{
    public function __construct(
        protected TariffBannedAddressRepositoryInterface $tariffBannedAddressRepo
    ) {}
    public function check(object $context = null): RuleResult
    {

        if (!($context instanceof HasTariff) || !($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to TariffAllowedForRegionNoBannedRule');

        if ($this->tariffBannedAddressRepo->isTariffBannedForAddress($context->getUser()->getAddress(), $context->getTariff()))
            return RuleResult::fail("Тариф {$context->getTariff()->getName()}({$context->getTariff()->getId()}) не доступен в регионе(бан) {$context->getUser()->getRegion()->getName()}");

        return RuleResult::ok();
    }
}

