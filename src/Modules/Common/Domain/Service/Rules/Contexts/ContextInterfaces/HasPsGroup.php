<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

use App\Modules\Common\Domain\Entity\PsGroup;

interface HasPsGroup
{
    public function getPsGroup(): PsGroup;
}