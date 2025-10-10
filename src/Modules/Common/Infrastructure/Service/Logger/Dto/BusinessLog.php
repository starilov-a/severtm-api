<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Dto;

use App\Modules\UserCabinet\Entity\WebAction;

class BusinessLog
{
    public function __construct(
        public int $userId,
        public int $actionId,
        public string $message,
        public bool $ok,
        public ?string $ip = null,
        public array $context = [],
        public $when = new \DateTime('now'),
    ) {}
}