<?php

namespace App\Tests\Functional\UserCabinet\APIv1\PaymentsController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetDebtTest extends TransactionalWebTestCase
{
    public function testGetDebt(){
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-debt');
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $data = json_decode(($this->client->getResponse()->getContent()),true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('debt', $data['data']);
        $this->assertIsInt($data['data']['debt']);
    }
}