<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Dto\Request\ServiceFilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProductService;

interface ProductServiceRepositoryInterface extends RepositoryInterface
{
    public function getServicesByFilter(ServiceFilterDto $filter = new ServiceFilterDto()): array;
    public function findByStrCode(string $code): ?ProductService;
    public function findVisible(): array;
}
