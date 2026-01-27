<?php

namespace App\Tests\Modules\UserCabinet\Controllers\APIv1;

use App\Tests\TransactionalWebTestCase;

class ServServiceControllerTest extends TransactionalWebTestCase {
    public function testGetAvailableServs(){

        $this->markTestSkipped('Устарело: Клиент не добавляет услугу через личный кабинет.');

        $client = static::createClient();
        $this->startTransaction();

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

    public function testGetCurrentServs(){
        $client = static::createClient();
        $this->startTransaction();
        $this->loginClient($client);

        $client->request('GET', '/user-cabinet/get-current-servs');
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data['data']);
        
        if(empty($data['data'])){
            $this->assertEmpty($data['data']);

            return; // может же не быть ни одной услуги, верно?
        }

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
                $this->assertIsString($arr['code']);

            foreach ($arr['modes'] as $mod) {
                $this->assertArrayHasKey('usmid', $mod);
                $this->assertArrayHasKey('name', $mod);
                $this->assertArrayHasKey('type', $mod);

                $this->assertIsInt(actual: $mod['usmid']);
                $this->assertIsString($mod['name']);
                $this->assertIsString($mod['type']);
                
                if(!is_null($mod['code']))
                    $this->assertIsString($mod['code']);
            }
        }
    }

    public function testAddAvailableServ(){
        $client = static::createClient();
        $this->startTransaction();

        $this->loginClient($client);

        $client->request('POST', '/user-cabinet/add-available-serv', [], [], [
                'CONTENT_TYPE' => 'application/json',
            ], json_encode([
                'uid'       =>  $this->getTestUserId(),
                'mode_id'   =>  $this->getTestModeId(),
            ])
        );

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);


        // $this->assertIsArray($data['data']);
        
        // if(empty($data['data'])){
        //     $this->assertEmpty($data['data']);

        //     return; // может же не быть ни одной услуги, верно?
        // }

        // foreach ($data['data'] as $arr) {
        //     $this->assertIsArray($arr);
        //     $this->assertArrayHasKey('id', $arr);
        //     $this->assertArrayHasKey('name', $arr);
        //     $this->assertArrayHasKey('modes', $arr);
        //     $this->assertArrayHasKey('code', $arr);

        //     $this->assertIsInt($arr['id']);
        //     $this->assertIsString($arr['name']);
        //     $this->assertIsArray($arr['modes']);

        //     if(!is_null($arr['code']))
        //         $this->assertIsString($arr['code']);

        //     foreach ($arr['modes'] as $mod) {
        //         $this->assertArrayHasKey('usmid', $mod);
        //         $this->assertArrayHasKey('name', $mod);
        //         $this->assertArrayHasKey('type', $mod);

        //         $this->assertIsInt($mod['usmid']);
        //         $this->assertIsString($mod['name']);
        //         $this->assertIsString($mod['type']);
                
        //         if(!is_null($mod['code']))
        //             $this->assertIsString($mod['code']);
        //     }
        // }
    }

    // public function testDisableServ(){}

    // public function testEnableServ(){}


}