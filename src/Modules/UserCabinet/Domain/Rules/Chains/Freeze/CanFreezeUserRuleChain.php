<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Rules\Definitions\ProdDiscountTemp\DiscountTempIsEmptyRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff\TariffForOneMonthRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsNotJuridicalRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserMustNotBeBlockedRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\UserBalance\BalanceMustBePositiveRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\UserTask\IsNotIssetNewFreezeTaskRule;

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