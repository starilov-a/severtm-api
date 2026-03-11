<?php

namespace App\Modules\BuildermanCabinet\Application\Dto\Response;

use App\Modules\Common\Application\Dto\Dto;

class ApplicationListItemDto extends Dto
{
    public function __construct(
        public int $applicationId,
        public string $addressName,
        public string $builderLogin,
        public string $statusCode,
    ) {}
}