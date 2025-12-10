<?php

namespace App\Modules\Common\Domain\Service\Rules\Tariff;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\ModeAllowedForRegionContext;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\Rule;

class TariffAllowedForRegionRule extends Rule
{

    public function __construct(protected ModeAllowedForRegionRule $modeAllowedForRegionRule){}

    public function check(object $context): bool
    {

        if (!$context instanceof HasActionId || !$context instanceof HasTariff || !$context instanceof HasRegion || !$context instanceof HasUserId)
            throw new \LogicException('Wrong context passed to TariffAllowedForRegionRule');

        // TODO: Нужно сделать проверку на address_banned

        // throw new ImportantBusinessException($context->getUserId(),$context->getActionId(),"Тариф {$mode->getName()}({$mode->getId()}) не доступен в регионе {$region->getName()}");

        $this->modeAllowedForRegionRule->check(new ModeAllowedForRegionContext(
            $context->getUserId(),
            $context->getActionId(),
            $context->getTariff()->getProdServMode(),
            $context->getRegion()
        ));

        return true;
    }
}

