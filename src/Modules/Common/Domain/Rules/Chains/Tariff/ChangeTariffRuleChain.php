<?php

namespace App\Modules\Common\Domain\Rules\Chains\Tariff;

use App\Modules\Common\Domain\Rules\Definitions\Tariff\IssetRentProdModeNowForDisconnectRule;
use App\Modules\Common\Domain\Rules\Definitions\Tariff\TariffAllowedForRegionGroupRule;
use App\Modules\Common\Domain\Rules\Definitions\Tariff\TariffAllowedForRegionNoBannedRule;
use App\Modules\Common\Domain\Rules\Definitions\Tariff\TariffsForChangeAreSameRule;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ChangeTariffRuleChain extends RuleChain
{
    public function __construct(
        LoggerService                         $loggerService,

        TariffAllowedForRegionNoBannedRule    $tariffAllowedForRegionNoBannedRule,
        IssetRentProdModeNowForDisconnectRule $issetRentProdModeNowForDisconnectRule,
        TariffAllowedForRegionGroupRule       $tariffAllowedForRegionGroupRule,
        TariffsForChangeAreSameRule           $tariffsForChangeAreSameRule,
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($tariffAllowedForRegionNoBannedRule),
            new ChainRuleItem($issetRentProdModeNowForDisconnectRule),
            new ChainRuleItem($tariffAllowedForRegionGroupRule),
            new ChainRuleItem($tariffsForChangeAreSameRule),
        ];
    }
}