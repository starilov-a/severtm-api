<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Service\Dto\Request;

use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;

class TariffFilterDto extends FilterDto
{
    private ?bool $activeStatus = null;        // true → только активные сегодня
    private ?float $minPrice = null;            // > minPrice
    private array $groupCodes = [];
    private array $regionGroupCodes = [];
    private bool $excludeDisconnected = true;   // скипать тариф "отключен от сети"
    private string $orderBy = 't.price';        // поле сортировки
    private string $orderDir = 'ASC';

    public function setOrderDir(string $orderDir): void
    {
        $this->orderDir = $orderDir;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

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

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function getOrderDir(): string
    {
        return $this->orderDir;
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
