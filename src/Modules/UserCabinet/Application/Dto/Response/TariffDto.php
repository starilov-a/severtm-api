<?php

namespace App\Modules\UserCabinet\Application\Dto\Response;


use App\Modules\UserCabinet\Domain\Service\Dto\Dto;

class TariffDto extends Dto
{
    private ?int $id;
    private ?string $name ;
    private ?float $price;
    private ?bool $isActive;


    public function __construct(int $id, string|null $name, float|null $price, bool $isActive)
    {
        $this->id = $id ?? 0;
        $this->name = $name ?? '';
        $this->price = $price ?? 0;
        $this->isActive = $isActive ?? false;
    }

    public function getId(): ?int
    {
        return $this->id;
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
