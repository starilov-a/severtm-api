<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Service\ProdServModeService;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasBoolVar;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

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