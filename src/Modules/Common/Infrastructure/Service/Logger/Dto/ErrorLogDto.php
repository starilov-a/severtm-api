<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Dto;

class ErrorLogDto
{
    public function __construct(
        public string $message,
        public array $context,
        public $when = new \DateTime('now'),
    ) {}
}