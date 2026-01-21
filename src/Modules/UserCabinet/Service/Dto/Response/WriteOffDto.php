<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;



use App\Modules\Common\Domain\Entity\UserPayable;

use App\Modules\Common\Domain\Service\Dto\Dto;

class WriteOffDto extends Dto
{
    private int $id;
    private ?string $date;
    private float $amount;
    private string $prodServMode;


    /**
     * @param int $id
     * @param string $payableId
     * @param string|null $date
     */
    // ”казать
    public function __construct($writeOff)
    {
        $this->id = $writeOff->getId();
        $this->date = $writeOff->getChargedAt()->format('Y-m-d H:i:s');
        $this->amount = $writeOff->getAmount();
        $this->prodServMode = $writeOff->getServiceModeCost()->getMode()->getName();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserServMode(): string
    {
        return $this->prodServMode;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

}
