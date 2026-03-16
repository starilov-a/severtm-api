<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;

interface HasContract
{
    public function getContract(): Contract;
}