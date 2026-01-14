<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class TariffAllowedForRegionNoBannedRule extends Rule
{
    public function __construct(protected ModeAllowedForRegionRule $modeAllowedForRegionRule){}
    public function check(object $context = null): RuleResult
    {

        if (!($context instanceof HasTariff) || !($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to TariffAllowedForRegionRule');

        // TODO: Нужно сделать проверку на address_banned
        if (false)
            return RuleResult::fail("Тариф {$context->getTariff()->getName()}({$context->getTariff()->getId()}) не доступен в регионе {$context->getUser()->getRegion()->getName()}");

        return RuleResult::ok();
    }
}

