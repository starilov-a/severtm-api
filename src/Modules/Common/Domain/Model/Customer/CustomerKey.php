<?php

namespace App\Modules\Common\Domain\Model\Customer;

final class CustomerKey
{
    private function __construct(
        public readonly string $type,   // 'JURIDICAL' | 'FISICAL'
        public readonly string $value,  // INN для JURIDICAL, contractId для FISICAL
    ) {}

    public static function legalInn(string $inn): self
    {
        return new self('JURIDICAL', preg_replace('/\s+/', '', $inn));
    }

    public static function personContractId(int $contractId): self
    {
        return new self('FISICAL', (string)$contractId);
    }
}