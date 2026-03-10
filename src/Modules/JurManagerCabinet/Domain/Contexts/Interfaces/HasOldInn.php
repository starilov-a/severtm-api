<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces;

interface HasOldInn
{
    public function getOldInn(): string;
}