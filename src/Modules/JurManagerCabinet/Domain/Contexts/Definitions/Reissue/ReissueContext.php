<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Definitions\Reissue;

use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasNewInn;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasOldInn;

class ReissueContext implements HasNewInn, HasOldInn
{
    public function __construct(
        protected string $newInn,
        protected string $oldInn,
    ) {}

    public function getNewInn(): string
    {
        return $this->newInn;
    }

    public function getOldInn(): string
    {
        return $this->oldInn;
    }
}