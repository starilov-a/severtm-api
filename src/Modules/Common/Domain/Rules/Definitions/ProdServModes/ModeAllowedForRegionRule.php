<?php

namespace App\Modules\Common\Domain\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\Common\Domain\Contexts\Interfaces\HasRegion;
use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class ModeAllowedForRegionRule extends Rule
{

    public function __construct(protected ProdServModeRepository $prodServModeRepository){}

    public function check(object $context = null): RuleResult
    {
        /** @var HasRegion & HasProdServMode $context */
        if (!($context instanceof HasProdServMode) || !($context instanceof HasRegion))
            throw new \LogicException('Wrong context passed to ModeAllowedForRegionRule');


        return RuleResult::ok();
    }
}