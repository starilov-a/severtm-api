<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;
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
        protected Connection $connection,
    ) {}

    public function check(object $context = null): RuleResult
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
