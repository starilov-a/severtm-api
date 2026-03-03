<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto;

class BusinessLogDto
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