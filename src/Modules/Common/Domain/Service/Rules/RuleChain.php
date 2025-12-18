<?php

namespace App\Modules\Common\Domain\Service\Rules;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class RuleChain implements RuleChainInterface
{
    /** @var ChainRuleItem[] */
    protected array $items = [];
    public function __construct(
        protected readonly LoggerService $loggerService
    ) {}

    /** @var HasWebAction & HasMaster $context */
    public function checkAll(object $context): bool
    {
        if (!($context instanceof HasWebAction) || !($context instanceof HasMaster))
            throw new \LogicException('Wrong context passed to RuleChain');

        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            if ($result->ok)
                continue;

            if ($item->mode === RuleMode::SOFT) {
                $this->loggerService->businessLog(new BusinessLogDto(
                    $context->getMaster()->getId() ?? 0,
                    $context->getWebAction()->getId() ?? 0,
                    $result->message,
                    false
                ));

                return false;
            }


            throw new ImportantBusinessException(
                $context->getMaster()->getId() ?? 0,
                $context->getWebAction()->getId() ?? 0,
                $result->message ?? 'Ошибка бизнес-правила'
            );
        }

        return true;
    }
}