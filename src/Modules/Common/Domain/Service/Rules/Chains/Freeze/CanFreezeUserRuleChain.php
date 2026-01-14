<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Service\Rules\Definitions\ProdDiscountTemp\DiscountTempIsEmptyRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Tariff\TariffForOneMonthRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotJuridicalRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserMustNotBeBlockedRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\UserBalance\BalanceMustBePositiveRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\UserTask\IsNotIssetNewFreezeTaskRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class CanFreezeUserRuleChain extends RuleChain
{
    public function __construct(
        LoggerService               $loggerService,

        UserIsNotJuridicalRule      $userIsNotJuridicalRule,
        UserIsNotFrozenRule         $userIsNotFrozenRule,
        UserMustNotBeBlockedRule    $userMustNotBeBlockedRule,
        IsNotIssetNewFreezeTaskRule $isNotIssetNewFreezeTaskRule,
        BalanceMustBePositiveRule   $balanceMustBePositive,
        DiscountTempIsEmptyRule     $discountTempIsEmptyRule,
        TariffForOneMonthRule       $tariffForOneMonthRule,
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsNotJuridicalRule, RuleMode::SOFT),
            new ChainRuleItem($userIsNotFrozenRule, RuleMode::SOFT),
            new ChainRuleItem($userMustNotBeBlockedRule, RuleMode::SOFT),
            new ChainRuleItem($isNotIssetNewFreezeTaskRule, RuleMode::SOFT),
            new ChainRuleItem($balanceMustBePositive, RuleMode::SOFT),
            new ChainRuleItem($discountTempIsEmptyRule, RuleMode::SOFT),
            new ChainRuleItem($tariffForOneMonthRule, RuleMode::SOFT)
        ];
    }
}