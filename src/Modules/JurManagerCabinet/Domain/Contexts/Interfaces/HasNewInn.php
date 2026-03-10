<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces;

interface HasNewInn
{
    public function getNewInn(): string;
}