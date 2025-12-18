<?php

namespace App\Modules\Common\Domain\Service\Rules\Results;

class RuleResult
{
    private function __construct(
        public readonly bool $ok,
        public readonly ?string $message = null,
        public readonly array $meta = []
    ){}

    public static function ok(): self
    {
        return new self(true);
    }

    public static function fail(string $message, array $meta = []): self
    {
        return new self(false, $message, $meta);
    }

}