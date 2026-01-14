<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Tariff;

use App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes\ModeAllowedForRegionRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\IssetRentProdModeNowForDisconnectRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\TariffAllowedForRegionNoBannedRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\TariffsForChangeAreSameRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ChangeTariffRuleChain extends RuleChain
{
    public function __construct(
        LoggerService                         $loggerService,

        TariffAllowedForRegionNoBannedRule    $tariffAllowedForRegionNoBannedRule,
        IssetRentProdModeNowForDisconnectRule $issetRentProdModeNowForDisconnectRule,
        ModeAllowedForRegionRule              $modeAllowedForRegionRule,
        TariffsForChangeAreSameRule           $tariffsForChangeAreSameRule,
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($tariffAllowedForRegionNoBannedRule),
            new ChainRuleItem($issetRentProdModeNowForDisconnectRule),
            new ChainRuleItem($modeAllowedForRegionRule),
            new ChainRuleItem($tariffsForChangeAreSameRule),
        ];
    }
}