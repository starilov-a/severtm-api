<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Service\Dto\Dto;

class OptionsUserServModeDto extends Dto
{
    // Устройство, если есть
    protected ?DeviceDto $deviceDto = null;

    //Кол-вол услуг
    protected int $countUnits = 1;

    //За какой фин период
    protected ?FinPeriod $finPeriod = null;

    protected ?string $comment = null;

    public function getDeviceDto(): ?DeviceDto
    {
        return $this->deviceDto;
    }

    public function setDevice(?DeviceDto $deviceDto): void
    {
        $this->deviceDto = $deviceDto;
    }

    public function getCountUnits(): int
    {
        return $this->countUnits;
    }

    public function setCountUnits(int $countUnits): void
    {
        $this->countUnits = $countUnits;
    }

    public function getFinPeriod(): ?FinPeriod
    {
        return $this->finPeriod;
    }

    public function setFinPeriod(?FinPeriod $finPeriod): void
    {
        $this->finPeriod = $finPeriod;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
    public function getComment(): ?string
    {
        return $this->comment;
    }
}