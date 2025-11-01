<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;

use App\Modules\UserCabinet\Service\Dto\Dto;

class TariffDto extends Dto
{
    private ?string $name ;
    private ?float $price;
    private ?bool $isActive;


    public function __construct(string|null $name, float|null $price, bool $isActive)
    {
        $this->name = $name ?? '';
        $this->price = $price ?? 0;
        $this->isActive = $isActive ?? false;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
