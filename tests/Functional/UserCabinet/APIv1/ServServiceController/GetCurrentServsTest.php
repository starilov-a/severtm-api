<?php

namespace App\Tests\Functional\UserCabinet\APIv1\ServServiceController;

use App\Tests\Functional\TransactionalWebTestCase;

class GetCurrentServsTest extends TransactionalWebTestCase {

    public function testGetCurrentServs(){
        $testUserId = $this->em->getConnection()->fetchOne(<<<SQL
            SELECT u.id
            FROM users u
            JOIN user_serv_modes usm ON usm.uid = u.id
            JOIN fin_periods fp ON fp.id = usm.fid AND fp.is_current = 1
            JOIN prod_serv_modes psm ON psm.id = usm.srvmode_id AND psm.srv_id != 4 
            WHERE u.is_juridical = 0 AND u.block = 0
            ORDER BY u.id DESC
            LIMIT 1;
        SQL);

        if (!$testUserId)
            self::markTestIncomplete('Нет пользователя frozen с историей заморозки для проверки.');

        $testUser = $this->userRepo->find((int)$testUserId);

        $this->loginClient($this->client, $testUser);

        $this->client->request('GET', '/user-cabinet/get-current-servs');
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($this->client->getResponse()->getContent(), true);
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
}
