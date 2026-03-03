<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasUserId
{
    public function getUserId(): int;
}