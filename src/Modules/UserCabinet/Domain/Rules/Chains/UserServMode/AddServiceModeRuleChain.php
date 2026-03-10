<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\UserServMode;

use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Rules\Definitions\FinPeriod\FinPeriodMustBeIssetRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes\JurStatusIsCorrectRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes\UnitsMustBePositiveRule;
use App\Modules\UserCabinet\Domain\Rules\Results\ChainRuleItem;
use App\Modules\UserCabinet\Domain\Rules\RuleChain;

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