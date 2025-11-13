<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Service\ServService;

class ServModeFilterDto extends FilterDto
{
    private array $groupCodes = [];
    private ?ServService $servService = null;
    public function getGroupCodes(): array
    {
        return $this->groupCodes;
    }
    public function addGroupCode(string $groupCode): void
    {
        $this->groupCodes[] = $groupCode;
    }
    public function getServService(): ?ServService
    {
        return $this->servService;
    }

    public function setServService(ServService $servService): void
    {
        $this->servService = $servService;
    }
}