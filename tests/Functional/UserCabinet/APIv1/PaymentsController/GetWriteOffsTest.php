<?php

namespace App\Tests\Functional\UserCabinet\APIv1\PaymentsController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetWriteOffsTest extends TransactionalWebTestCase
{
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
}