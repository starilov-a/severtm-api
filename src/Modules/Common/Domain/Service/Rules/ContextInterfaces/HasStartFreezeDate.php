<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

interface HasStartFreezeDate
{
    public function getStartFreezeDate(): \DateTimeImmutable;
}