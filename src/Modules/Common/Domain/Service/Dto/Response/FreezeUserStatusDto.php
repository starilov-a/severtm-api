<?php

namespace App\Modules\Common\Domain\Service\Dto\Response;

use App\Modules\Common\Domain\Entity\BlockState;
use App\Modules\Common\Domain\Service\Dto\Dto;

class FreezeUserStatusDto extends Dto
{
    public function __construct(
        protected bool $status,
        protected bool $availableFreeze,
        protected bool $availableUnfreeze,
        protected ?string $reasonCantFreeze = null,
        protected ?string $reasonCantUnfreeze = null
    ) {}

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function setAvailableFreeze(bool $availableFreeze): void
    {
        $this->availableFreeze = $availableFreeze;
    }

    public function setAvailableUnfreeze(bool $availableUnfreeze): void
    {
        $this->availableUnfreeze = $availableUnfreeze;
    }

    public function setReasonCantFreeze(?string $reasonCantFreeze): void
    {
        $this->reasonCantFreeze = $reasonCantFreeze;
    }

    public function setReasonCantUnfreeze(?string $reasonCantUnfreeze): void
    {
        $this->reasonCantUnfreeze = $reasonCantUnfreeze;
    }
}