<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\FinPeriodMustBeIssetRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\JurStatusIsCorrectRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\UnitsMustBePositiveRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class AddServiceModeRuleChain extends RuleChain
{
    public function __construct(
        LoggerService $loggerService,
        UnitsMustBePositiveRule $unitsMustBePositiveRule,
        FinPeriodMustBeIssetRule $finPeriodMustBeIssetRule,
        JurStatusIsCorrectRule $jurStatusIsCorrectRule,
        ModeAllowedForRegionRule $modeAllowedForRegionRule,
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($unitsMustBePositiveRule),
            new ChainRuleItem($finPeriodMustBeIssetRule),
            new ChainRuleItem($jurStatusIsCorrectRule),
            new ChainRuleItem($modeAllowedForRegionRule)
        ];
    }
}