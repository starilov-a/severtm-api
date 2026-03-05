<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Dto;

use Psr\Log\LogLevel;

class ErrorLogDto
{
    public function __construct(
        public string $message,
        public array $context = [],
        public string $level = LogLevel::ERROR,
        public array $labels = [],
        public ?\Throwable $throwable = null,
        public $when = new \DateTime('now'),
    ) {}
}
