<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

interface HasUserId
{
    public function getUserId(): int;
}