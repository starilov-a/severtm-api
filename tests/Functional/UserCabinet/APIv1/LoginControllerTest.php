<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Tests\Functional\TransactionalWebTestCase;
use App\Tests\Support\Dto\TestUserCredentials;

class LoginControllerTest extends TransactionalWebTestCase
{
    public function testLoginMissingData(): void
    {
        $this->loginClient($this->client);

        $this->client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));

        $this->assertResponseStatusCodeSame(400);
    }

    public function testLoginWrongCredentials(): void
    {
        $this->client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => 'несуществующий_логин',
            'password' => 'wrong',
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testSuccessfulLogin(): void
    {
        $testUser = $this->userRepo->findOneBy(['blockState' => 0, 'isJuridical' => 0], ['id' => 'DESC']);
        $creds = $this->createCredentials($testUser);

        $this->client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => $creds->login,
            'password' => $creds->password,
        ]));

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(true, $responseData['data']['success']);
    }

    public function testLogout(): void
    {
        $this->loginClient($this->client);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Логаут
        $this->client->request('POST', '/user-cabinet/logout');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(true, $responseData['data']['success']);
    }
    
}