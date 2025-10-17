<?php

namespace App\Modules\Common\Infrastructure\Exception;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class ImportantBusinessException extends BusinessException
{
    protected int $userId;
    protected int $actionId;
    public function __construct(
        int $userId,
        int $actionId,
        string $message
    ) {
        $this->userId = $userId;
        $this->actionId = $actionId;

        parent::__construct($message);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getStatus(): bool
    {
        return false;
    }

    public function getIp(): string
    {
        return UserSessionService::getUserIp();
    }
}