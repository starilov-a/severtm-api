<?php

namespace App\Modules\UserCabinet\Service\Dto;

use App\Shared\Traits\ArrayableTrait;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;

abstract class Dto
{
    use ArrayableTrait;
}