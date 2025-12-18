<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\User;

use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

/**
 * Бизнес-правило:
 * Клиент не должен быть заблокирован
 */
class UserMustNotBeBlockedRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService,
        protected BlockStateRepository $blockStateRepo,
    ){}
    /** @var HasWebAction & HasUser $context */
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        if ($context->getUser()->getBlockState() === $this->blockStateRepo->findByCode('blocked'))
            RuleResult::fail("Пользователь является заблокированным");

        return RuleResult::ok();
    }
}