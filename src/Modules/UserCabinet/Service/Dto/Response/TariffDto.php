<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;

use App\Modules\UserCabinet\Service\Dto\Dto;

class TariffDto extends Dto
{
    private $name ;
    private $price;

    public function __construct(string|null $name, float|null $price)
    {
        $this->name = $name ?? '';
        $this->price = $price ?? 0;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}