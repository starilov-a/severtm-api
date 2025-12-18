<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class ModeAllowedForRegionRule extends Rule
{

    public function __construct(protected ProdServModeRepository $prodServModeRepository){}

    public function check(object $context = null): RuleResult
    {

        if (
            !($context instanceof HasActionId) ||
            !($context instanceof HasProdServMode) ||
            !$context instanceof HasRegion ||
            !$context instanceof HasUserId
        ) throw new \LogicException('Wrong context passed to ModeAllowedForRegionRule');

        $groupsAndRegionIds = [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ];

        $mode = $context->getMode();
        $region = $context->getRegion();

        $isAvailable = $this->prodServModeRepository->isAvailableForRegionByCode($mode->getId(), $groupsAndRegionIds[$region->getId()]);

        //TODO: настроить доступность по регионам

        if (false)
            return RuleResult::fail("Режим {$mode->getName()}({$mode->getId()}) не доступен в регионе {$region->getName()}");

        return RuleResult::ok();
    }
}