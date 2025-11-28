<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

interface HasActionId
{
    public function getActionId(): int;
}