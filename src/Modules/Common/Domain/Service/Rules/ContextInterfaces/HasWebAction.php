<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\WebAction;

interface HasWebAction
{
    public function getWebAction(): WebAction;
}