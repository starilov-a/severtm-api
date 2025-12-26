<?php

namespace App\Tests\Modules\UserCabinet\Controllers\APIv1;

use App\Tests\TransactionalWebTestCase;

class LoginControllerTest extends TransactionalWebTestCase
{
    public function testLoginMissingData(): void
    {
        $client = static::createClient();
        $this->startTransaction();

        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));

        $this->assertResponseStatusCodeSame(400);
    }

    public function testLoginWrongCredentials(): void
    {
        $client = static::createClient();
        $this->startTransaction();

        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => 'несуществующий_логин',
            'password' => 'wrong',
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();

        $this->startTransaction();

        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => self::$testLogin ?: '',
            'password' => self::$testPassword ?: '',
        ]));

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('User login', $responseData['message']);
    }

    public function testLogout(): void
    {
        $client = static::createClient();
        $this->startTransaction();

        // Логинимся
        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => self::$testLogin ?: '',
            'password' => self::$testPassword ?: '',
        ]));

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Логаут
        $client->request('POST', '/user-cabinet/logout');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('User logout', $responseData['message']);
    }
    
}