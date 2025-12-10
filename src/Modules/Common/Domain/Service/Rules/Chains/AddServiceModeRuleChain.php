<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\ProdServModes\FinPeriodMustBeIssetRule;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\JurStatusIsCorrectRule;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\UnitsMustBePositiveRule;
use App\Modules\Common\Domain\Service\Rules\RuleChain;

class AddServiceModeRuleChain extends RuleChain
{
    public function __construct(
        UnitsMustBePositiveRule $unitsMustBePositiveRule,
        FinPeriodMustBeIssetRule $finPeriodMustBeIssetRule,
        JurStatusIsCorrectRule $jurStatusIsCorrectRule,
        ModeAllowedForRegionRule $modeAllowedForRegionRule,
    )
    {
        $this->rules = [
            $unitsMustBePositiveRule,
            $finPeriodMustBeIssetRule,
            $jurStatusIsCorrectRule,
            $modeAllowedForRegionRule
        ];
    }
}