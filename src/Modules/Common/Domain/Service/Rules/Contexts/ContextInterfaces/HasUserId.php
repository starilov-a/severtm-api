<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasUserId
{
    public function getUserId(): int;
}