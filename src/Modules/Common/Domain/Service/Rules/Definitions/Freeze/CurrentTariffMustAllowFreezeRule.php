<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use Doctrine\DBAL\Connection;

/**
 * Бизнес-правило:
 * заморозка разрешена только тем тарифам/режимам, которые помечены параметром группы group_freeze=1.
 *
 * СЕЙЧАС ВСЕ ТАРИФЫ МОГУТ БЫТЬ ЗАМОРОЖЕННЫМИ
 */
class CurrentTariffMustAllowFreezeRule extends Rule
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function check(object $context): RuleResult
    {
        if (!($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to CurrentTariffMustAllowFreezeRule');

        return RuleResult::ok();
        // в будущем может быть стоит переделать, сейчас не активно но логика предусмотрена

        $sql = <<<SQL
            SELECT 1
            FROM users u
            JOIN tariffs_current t ON t.id = u.tariff
            JOIN psm_belong_groups pbg ON pbg.srvmode_id = t.srvmode_id
            JOIN psm_group_parameters pgp ON pgp.psm_group_id = pbg.psm_group_id
            WHERE u.id = :uid
              AND pgp.param_code = 'group_freeze'
              AND pgp.param_value = '1'
            LIMIT 1
        SQL;

        $allowed = false !== $this->connection->fetchOne($sql, ['uid' => $context->getUserId()]);

        if (!$allowed)
            return RuleResult::fail('Текущий тариф не поддерживает заморозку');


        return RuleResult::ok();
    }
}
