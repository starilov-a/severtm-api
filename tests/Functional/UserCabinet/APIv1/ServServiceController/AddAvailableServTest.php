<?php

namespace App\Tests\Functional\UserCabinet\APIv1\ServServiceController;

use App\Tests\Functional\TransactionalWebTestCase;

class AddAvailableServTest extends TransactionalWebTestCase
{
    public function testAddAvailableServ(){
        $this->markTestSkipped('Устарело: Клиент не может добавлять себе услуги.');

        $this->loginClient($this->client);

        $this->client->request('POST', '/user-cabinet/add-available-serv', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
                'mode_id'   =>  4, // тест на интернет
            ])
        );

        $this->assertResponseStatusCodeSame(200);
    }
}