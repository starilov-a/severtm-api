<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;



use App\Modules\Common\Domain\Entity\WriteOff;
use App\Modules\Common\Domain\Service\Dto\Dto;

class WriteOffDto extends Dto
{
    private int $id;
    private ?string $payableId;
    private ?string $date;


    /**
     * @param int $id
     * @param string $payableId
     * @param string|null $date
     */
    public function __construct(WriteOff $writeOff)
    {
        $this->id = $writeOff->getId();
        $this->payableId = $writeOff->getPayableId();
        $this->date = $writeOff->getChargedAt()->format('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPayableId(): string
    {
        return $this->payableId;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

}
