<?php

namespace App\Modules\UserCabinet\Domain\Rules;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Rules\Interfaces\RuleChainInterface;
use App\Modules\UserCabinet\Domain\Rules\Results\ChainRuleItem;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleMode;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

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

            $exceptionClass = $item->exceptionClass ?? ImportantBusinessException::class;

            throw new $exceptionClass(
                $context->getMaster()->getId() ?? 0,
                $context->getWebAction()->getId() ?? 0,
                $result->message ?? 'Ошибка бизнес-правил'
            );
        }

        return true;
    }

    public function checkAllWithResult(object $context): RuleResult
    {
        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            if ($result->ok)
                continue;

            return $result;
        }
        return RuleResult::ok();
    }

    public function checkAny(object $context): bool
    {
        if (!($context instanceof HasWebAction) || !($context instanceof HasMaster)) {
            throw new \LogicException('Wrong context passed to RuleChain');
        }

        $firstSoftFail = null;     /** @var RuleResult|null $firstSoftFail */
        $firstHardFail = null;     /** @var array{item: ChainRuleItem, result: RuleResult}|null $firstHardFail */

        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            // Успех: хотя бы одно правило прошло → пропускаем
            if ($result->ok) {
                return true;
            }

            // Запоминаем первый soft fail — пригодится для лога/возврата
            if ($item->mode === RuleMode::SOFT) {
                $firstSoftFail ??= $result;
                continue;
            }

            // HARD fail: запомним первый hard fail, но продолжаем,
            // вдруг какое-то следующее правило пройдет и мы вернем true.
            $firstHardFail ??= ['item' => $item, 'result' => $result];
        }

        // Если дошли сюда — значит ВСЕ правила провалились

        // HARD приоритетнее: кидаем исключение
        if ($firstHardFail !== null) {
            $item = $firstHardFail['item'];
            $result = $firstHardFail['result'];

            $exceptionClass = $item->exceptionClass ?? ImportantBusinessException::class;

            throw new $exceptionClass(
                $context->getMaster()->getId() ?? 0,
                $context->getWebAction()->getId() ?? 0,
                $result->message ?? 'Ошибка бизнес-правил'
            );
        }

        // Только SOFT провалы → логируем один раз и возвращаем false
        $msg = $firstSoftFail?->message ?? 'Ни одно правило не прошло';
        $this->loggerService->businessLog(new BusinessLogDto(
            $context->getMaster()->getId() ?? 0,
            $context->getWebAction()->getId() ?? 0,
            $msg,
            false
        ));

        return false;
    }

    public function checkAnyWithResult(object $context): RuleResult
    {
        // В этом методе НЕ бросаем исключения (по названию WithResult).
        // Просто возвращаем ok или причину.
        if (!($context instanceof HasWebAction) || !($context instanceof HasMaster))
            throw new \LogicException('Wrong context passed to RuleChain');


        $firstFail = null; /** @var RuleResult|null $firstFail */

        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            if ($result->ok)
                return RuleResult::ok();

            $firstFail ??= $result;
        }

        return $firstFail ?? RuleResult::fail('No rules configured'); // если items пустой
    }
}