<?php

namespace App\Tests\Functional\UserCabinet\APIv1\PaymentsController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetBalanceTest extends TransactionalWebTestCase
{
    public function testGetBalance(): void
    {
        $this->loginClient($this->client);


        $this->client->request('GET', '/user-cabinet/get-balance');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('balance', $data['data']);
        $this->assertIsInt($data['data']['balance']);
    }
}