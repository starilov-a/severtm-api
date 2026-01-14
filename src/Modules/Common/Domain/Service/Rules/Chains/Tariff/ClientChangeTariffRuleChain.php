<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Tariff;

use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\CanBeChangedByClientRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\TariffIsNotDisconnectedRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
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