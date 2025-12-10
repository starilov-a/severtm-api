<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Service\Dto\Request;

class TariffFilterDto extends FilterDto
{
    private ?bool $activeStatus = null;        // true → только активные сегодня
    private ?float $minPrice = null;            // > minPrice
    private array $groupCodes = [];
    private array $regionGroupCodes = [];
    private bool $excludeDisconnected = true;   // скипать тариф "отключен от сети"
    public function setExcludeDisconnected(bool $excludeDisconnected): void
    {
        $this->excludeDisconnected = $excludeDisconnected;
    }

    public function addGroupCodes(string $groupCode): void
    {
        $this->groupCodes[] = $groupCode;
    }

    public function setMinPrice(?float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    public function setActiveStatus(?bool $activeStatus): void
    {
        $this->activeStatus = $activeStatus;
    }

    public function getActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function getGroupCodes(): array
    {
        return $this->groupCodes;
    }

    public function isExcludeDisconnected(): bool
    {
        return $this->excludeDisconnected;
    }

    public function getRegionGroupCodes(): array
    {
        return $this->regionGroupCodes;
    }

    public function addRegionGroupCodes(string $regionGroupCode): void
    {
        $this->regionGroupCodes[] = $regionGroupCode;
    }


}
