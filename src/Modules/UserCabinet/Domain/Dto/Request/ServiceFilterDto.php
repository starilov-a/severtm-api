<?php

namespace App\Modules\UserCabinet\Domain\Dto\Request;

class ServiceFilterDto extends FilterDto
{
    private array $groupCodes = [];

    private ?bool $visibleStatus = null;

    public function getGroupCodes(): array
    {
        return $this->groupCodes;
    }
    public function addGroupCode(string $groupCode): void
    {
        $this->groupCodes[] = $groupCode;
    }

    public function getVisibleStatus(): ?bool
    {
        return $this->visibleStatus;
    }

    public function setVisibleStatus(?bool $visibleStatus): void
    {
        $this->visibleStatus = $visibleStatus;
    }

}