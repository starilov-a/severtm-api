<?php

namespace App\Tests\Functional\UserCabinet\APIv1\ServServiceController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetAvailableServsTest extends TransactionalWebTestCase
{
    public function testGetAvailableServs(){
        $this->markTestSkipped('Устарело: Клиент не может видеть список услуг.');

        $this->loginClient($client);

        $client->request('GET', '/user-cabinet/get-available-servs');
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data['data'], 'Список сервисов не должен быть пустым'); // Список сервисов не может же быть пустым?

        foreach ($data['data'] as $arr) {
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('id', $arr);
            $this->assertArrayHasKey('name', $arr);
            $this->assertArrayHasKey('modes', $arr);
            $this->assertArrayHasKey('code', $arr);

            $this->assertIsInt($arr['id']);
            $this->assertIsString($arr['name']);
            $this->assertIsArray($arr['modes']);

            if(!is_null($arr['code']))
                $this->assertIsString($arr['code']); // точно ли может быть null?

            foreach ($arr['modes'] as $mod) {
                $this->assertArrayHasKey('id', $mod);
                $this->assertArrayHasKey('name', $mod);
                $this->assertArrayHasKey('code', $mod);

                $this->assertIsInt($mod['id']);
                $this->assertIsString($mod['name']);

                if(!is_null($mod['code']))
                    $this->assertIsString($mod['code']); // точно ли может быть null?
            }
        }
    }
}