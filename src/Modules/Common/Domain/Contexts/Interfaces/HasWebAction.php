<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\WebAction;

interface HasWebAction
{
    public function getWebAction(): WebAction;
}