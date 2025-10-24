<?php

namespace App\Modules\UserCabinet\Service\Dto\Request;

use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;

class ServModeFilterDto extends FilterDto
{
    private array $groupCodes = [];
    public function getGroupCodes(): array
    {
        return $this->groupCodes;
    }
    public function addGroupCode(string $groupCode): void
    {
        $this->groupCodes[] = $groupCode;
    }
}