<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasUserId
{
    public function getUserId(): int;
}