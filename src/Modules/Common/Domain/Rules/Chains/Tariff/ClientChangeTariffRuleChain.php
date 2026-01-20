<?php

namespace App\Modules\Common\Domain\Rules\Chains\Tariff;

use App\Modules\Common\Domain\Rules\Definitions\Tariff\CanBeChangedByClientRule;
use App\Modules\Common\Domain\Rules\Definitions\Tariff\TariffIsNotDisconnectedRule;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ClientChangeTariffRuleChain extends RuleChain
{
    public function __construct(
        LoggerService               $loggerService,

        TariffIsNotDisconnectedRule             $tariffIsNotDisconnectedRule,
        CanBeChangedByClientRule                $canBeChangedByClientRule
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($tariffIsNotDisconnectedRule),
            new ChainRuleItem($canBeChangedByClientRule),
        ];
    }
}