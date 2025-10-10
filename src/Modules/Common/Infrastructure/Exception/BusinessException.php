<?php

namespace App\Modules\Common\Infrastructure\Exception;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Entity\WebAction;

abstract class BusinessException extends \DomainException
{
    protected int $userId;
    protected string $webLogMessage;
    protected WebAction $webAction;

    public function __construct(
        string $exceptionMessage,
        string $webLogMessage,
        WebAction $webAction,
        int $userId = null
    ) {
        $this->webLogMessage = $webLogMessage;
        $this->webAction = $webAction;
        if (!isset($userId))
            $this->userId = UserSessionService::getUserId();

        parent::__construct($exceptionMessage);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getWebLogMessage(): string
    {
        return $this->webLogMessage;
    }

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }
}