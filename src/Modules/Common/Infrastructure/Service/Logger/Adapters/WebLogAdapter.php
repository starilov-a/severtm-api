<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Adapters;

use App\Modules\Common\Infrastructure\Service\Logger\BusinessLoggerInterface;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLog;
use App\Modules\UserCabinet\Entity\WebAction;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

class WebLogAdapter implements BusinessLoggerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {}

    /**
     * Используем запись в web_log
     */
    public function log(BusinessLog $log): void
    {
        $this->em->getConnection()->executeStatement(
            'INSERT INTO web_log (uid, ip, act_id, act_time, act_message, act_result)
             VALUES (:uid, :ip, :act, :time, :msg, :res)',
            [
                'uid'  => $log->userId,
                'ip'   => mb_substr($log->ip ??= 0, 0, 16),
                'act'  => $log->actionId,
                'time' => $log->when->format('Y-m-d H:i:s'),
                'msg'  => $log->message,
                'res'  => $log->ok,
            ],
            [
                'uid'  => ParameterType::INTEGER,
                'ip'   => ParameterType::STRING,
                'act'  => ParameterType::INTEGER,
                'time' => ParameterType::STRING,
                'msg'  => ParameterType::STRING,
                'res'  => ParameterType::INTEGER,
            ]
        );
    }
}