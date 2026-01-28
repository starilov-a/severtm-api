<?php

namespace App\Tests\Functional\UserCabinet\APIv1\PaymentsController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetReplenishmentsTest extends TransactionalWebTestCase
{
    public function testGetReplenishments(){
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-replenishments');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);

        if (!empty($data['data'])) {
            $first = $data['data'][0];

            $this->assertArrayHasKey('id', $first);
            $this->assertIsInt($first['id']);

            $this->assertArrayHasKey('login', $first);
            $this->assertIsString($first['login']);

            $this->assertArrayHasKey('additionalInformation', $first);
            $this->assertIsString($first['additionalInformation']);

            $this->assertArrayHasKey('paymentType', $first);
            $this->assertIsString($first['paymentType']);

            $this->assertArrayHasKey('comment', $first);
            $this->assertIsString($first['comment']);

            $this->assertArrayHasKey('amount', $first);
            $this->assertIsInt($first['amount']);

            $this->assertArrayHasKey('date', $first);
            $this->assertIsString($first['date']);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $first['date']);
        }
    }
}