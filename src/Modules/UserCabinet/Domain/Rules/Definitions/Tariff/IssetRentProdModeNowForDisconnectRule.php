<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;

/**
 * Есть ли активная услуга аренды в текущем фин периоде
 *
 *
 */
class IssetRentProdModeNowForDisconnectRule extends Rule
{
    public function __construct(
        protected UserServModeRepositoryInterface $userServModeRepo,
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