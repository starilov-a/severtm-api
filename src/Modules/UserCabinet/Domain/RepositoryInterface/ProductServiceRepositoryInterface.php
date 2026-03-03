<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\ProductService;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServiceFilterDto;

interface ProductServiceRepositoryInterface extends RepositoryInterface
{
    public function getServicesByFilter(ServiceFilterDto $filter = new ServiceFilterDto()): array;
    public function findByStrCode(string $code): ?ProductService;
    public function findVisible(): array;
}
