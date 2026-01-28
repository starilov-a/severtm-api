<?php

namespace App\Tests\Functional\UserCabinet\APIv1\LoginController;

use App\Tests\Functional\TransactionalWebTestCase;

class LogoutTest extends TransactionalWebTestCase
{
    public function testLogout(): void
    {
        $this->loginClient($this->client);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->client->request('POST', '/user-cabinet/logout');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(true, $responseData['data']['success']);
    }
}