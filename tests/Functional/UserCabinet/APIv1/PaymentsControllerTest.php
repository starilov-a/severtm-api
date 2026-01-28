<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Tests\Functional\TransactionalWebTestCase;
use App\Tests\Support\Dto\TestUserCredentials;

class PaymentsControllerTest extends TransactionalWebTestCase {
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
    
    public function testGetWriteOffs(){
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-write-offs');
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseContent = $this->client->getResponse()->getContent();
        $data = json_decode($responseContent, true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);

        if (!empty($data['data'])) {
            $first = $data['data'][0];

            $this->assertArrayHasKey('id', $first);
            $this->assertIsInt($first['id']);

            $this->assertArrayHasKey('date', $first);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $first['date']);

            $this->assertArrayHasKey('amount', $first);
            $this->assertIsInt($first['amount']);

            $this->assertArrayHasKey('prodServMode', $first);
            $this->assertIsString($first['prodServMode']);
        }
    }

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
