<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\PsGroup;

interface HasPsGroup
{
    public function getPsGroup(): PsGroup;
}