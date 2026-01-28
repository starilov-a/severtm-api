<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Tests\Functional\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetStatusFreezeTest extends TransactionalWebTestCase
{
    public function testGetStatusFreeze(): void
    {
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-status-freeze');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertIsArray($payload['data']);
        $this->assertArrayHasKey('status', $payload['data']);
        $this->assertArrayHasKey('availableFreeze', $payload['data']);
        $this->assertArrayHasKey('availableUnfreeze', $payload['data']);
    }
}
