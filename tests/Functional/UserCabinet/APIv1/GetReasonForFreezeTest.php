<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Tests\Functional\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetReasonForFreezeTest extends TransactionalWebTestCase
{
    public function testGetReasonForFreeze(): void
    {
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-reason-for-freeze');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertIsArray($payload['data']);

        if (!empty($payload['data'])) {
            $first = $payload['data'][0];
            $this->assertArrayHasKey('id', $first);
            $this->assertArrayHasKey('name', $first);
        }
    }
}
