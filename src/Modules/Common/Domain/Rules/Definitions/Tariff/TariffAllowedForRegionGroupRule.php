<?php

namespace App\Modules\Common\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasRegion;
use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class TariffAllowedForRegionGroupRule extends Rule
{

    public function __construct(protected ProdServModeRepository $prodServModeRepository){}

    public function check(object $context = null): RuleResult
    {
        /** @var HasRegion & HasTariff $context */
        if (!($context instanceof HasTariff) || !($context instanceof HasRegion))
            throw new \LogicException('Wrong context passed to TariffAllowedForRegionGroupRule');

        $groupsAndRegionIds = [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ];

        $tariff = $context->getTariff();
        $region = $context->getRegion();
        $isAvailable = $this->prodServModeRepository->isAvailableForRegionByCode(
            $tariff->getProdServMode()->getId(),
            $groupsAndRegionIds[$region->getId()]
        );

        if (!$isAvailable)
            return RuleResult::fail("Тариф {$tariff->getName()}({$tariff->getId()}) не доступен в регионе {$region->getName()}");

        return RuleResult::ok();
    }
}