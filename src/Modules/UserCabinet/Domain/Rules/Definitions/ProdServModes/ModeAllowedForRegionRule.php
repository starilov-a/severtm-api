<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasRegion;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class ModeAllowedForRegionRule extends Rule
{

    public function __construct(protected ProdServModeRepositoryInterface $prodServModeRepository){}

    public function check(object $context = null): RuleResult
    {
        /** @var HasRegion & HasProdServMode $context */
        if (!($context instanceof HasProdServMode) || !($context instanceof HasRegion))
            throw new \LogicException('Wrong context passed to ModeAllowedForRegionRule');


        return RuleResult::ok();
    }
}