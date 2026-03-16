<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Reissue;

use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasContract;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasNewInn;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasOldInn;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasReissueDate;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;

class ReissueContext implements HasNewInn, HasOldInn, HasReissueDate, HasContract
{
    public function __construct(
        protected string $newInn,
        protected string $oldInn,
        protected \DateTimeImmutable $reissueDate,
        protected Contract $contract
    ) {}

    public function getNewInn(): string
    {
        return $this->newInn;
    }

    public function getOldInn(): string
    {
        return $this->oldInn;
    }

    public function getReissueDate(): \DateTimeImmutable
    {
        return $this->reissueDate;
    }

    public function getContract(): Contract
    {
        return $this->contract;
    }
}