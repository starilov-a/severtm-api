<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\User;

interface HasUser
{
    public function getUser(): User;

}