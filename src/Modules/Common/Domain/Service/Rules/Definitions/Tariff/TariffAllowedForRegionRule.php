<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ModeAllowedForRegionContext;
use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class TariffAllowedForRegionRule extends Rule
{

    public function __construct(protected ModeAllowedForRegionRule $modeAllowedForRegionRule){}

    public function check(object $context): RuleResult
    {

        if (!($context instanceof HasActionId) ||
            !($context instanceof HasTariff) ||
            !($context instanceof HasRegion) ||
            !($context instanceof HasUserId))
            throw new \LogicException('Wrong context passed to TariffAllowedForRegionRule');

        // TODO: Нужно сделать проверку на address_banned

        // throw new ImportantBusinessException($context->getUserId(),$context->getActionId(),"Тариф {$mode->getName()}({$mode->getId()}) не доступен в регионе {$region->getName()}");

        return RuleResult::ok();
    }
}

