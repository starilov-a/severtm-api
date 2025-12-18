<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasStartFreezeDate
{
    public function getStartFreezeDate(): \DateTimeImmutable;
}