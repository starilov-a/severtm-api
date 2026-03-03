<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasActionId
{
    public function getActionId(): int;
}