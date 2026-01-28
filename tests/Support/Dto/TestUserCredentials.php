<?php

namespace App\Tests\Support\Dto;

class TestUserCredentials
{
    public function __construct(
        public readonly int $uid,
        public readonly string $login,
        public readonly string $password
    ) {

    }
}