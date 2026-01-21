<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasActionId
{
    public function getActionId(): int;
}