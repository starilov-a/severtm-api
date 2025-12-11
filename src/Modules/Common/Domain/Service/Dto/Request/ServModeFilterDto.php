<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Entity\ProductService;
use App\Modules\Common\Domain\Service\ServService;

class ServModeFilterDto extends FilterDto
{
    private array $groupCodes = [];
    private ?ProductService $prodService = null;
    public function getGroupCodes(): array
    {
        return $this->groupCodes;
    }
    public function addGroupCode(string $groupCode): void
    {
        $this->groupCodes[] = $groupCode;
    }
    public function getProductService(): ?ProductService
    {
        return $this->prodService;
    }

    public function setProductService(ProductService $prodService): void
    {
        $this->prodService = $prodService;
    }
}