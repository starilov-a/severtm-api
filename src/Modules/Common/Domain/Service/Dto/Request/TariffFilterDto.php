<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Service\Dto\Request;

class TariffFilterDto extends FilterDto
{
    private ?bool $activeStatus = null;        // true → только активные сегодня
    private array $regionGroupCodes = [];
    private array $requiredGroupCodes = [];
    private bool $excludeDisconnected = true;   // скипать тариф "отключен от сети"
    public function setExcludeDisconnected(bool $excludeDisconnected): void
    {
        $this->excludeDisconnected = $excludeDisconnected;
    }

    public function isExcludeDisconnected(): bool
    {
        return $this->excludeDisconnected;
    }

    public function setActiveStatus(?bool $activeStatus): void
    {
        $this->activeStatus = $activeStatus;
    }

    public function getActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }

    public function getRegionGroupCodes(): array
    {
        return $this->regionGroupCodes;
    }

    public function addRegionGroupCodes(string $regionGroupCode): void
    {
        $this->regionGroupCodes[] = $regionGroupCode;
    }

    public function addRequiredGroupCode(string $groupCode): void
    {
        $this->requiredGroupCodes[] = $groupCode;
    }

    public function getRequiredGroupCodes(): array
    {
        return $this->requiredGroupCodes;
    }

}
