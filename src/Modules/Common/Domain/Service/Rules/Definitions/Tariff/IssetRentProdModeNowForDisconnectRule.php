<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

/**
 * Есть ли активная услуга аренды в текущем фин периоде
 *
 *
 */
class IssetRentProdModeNowForDisconnectRule extends Rule
{
    public function __construct(
        protected UserServModeRepository $userServModeRepo,
    ) {}

    public function check(object $context = null): RuleResult
    {
        /** @var HasUser & HasTariff $context */
        if (!($context instanceof HasTariff))
            throw new \LogicException('Wrong context passed to IssetRentProdModeNowForDisconnectRule');

        if($context->getTariff()->isDisconnected() && $this->userServModeRepo->hasRentNow($context->getUser()->getId()))
            return RuleResult::fail('Нельзя выбрать тариф отключен от сети. Присутствует услуга аренды');

        return RuleResult::ok();
    }
}