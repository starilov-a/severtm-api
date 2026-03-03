<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasBoolVar;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\UserCabinet\Domain\Service\ProdServModeService;

class JurStatusIsCorrectRule extends Rule
{
    public function __construct(
        protected ProdServModeService $prodServModeService,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasProdServMode) || !($context instanceof HasBoolVar))
            throw new \LogicException('Wrong context passed to JurStatusIsCorrectRule');


        $modeIsJuridical = $this->prodServModeService->isJuridical($context->getMode());

        if ($context->getBoolVar() !== $modeIsJuridical)
            return RuleResult::fail('Юридический признак не совпадает с признаком режима');

        return RuleResult::ok();
    }
}