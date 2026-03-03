<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Tariff;

use App\Modules\Common\Rules\Results\ChainRuleItem;
use App\Modules\Common\Rules\RuleChain;
use App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff\CanBeChangedByClientRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff\TariffIsNotDisconnectedRule;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

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