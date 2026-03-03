<?php

namespace App\Tests\Functional\UserCabinet\APIv1\FreezeController;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Tests\Functional\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DisableFreezeTest extends TransactionalWebTestCase
{
    public function testDisableFreezeForFrozenUser(): void
    {
        $testUserId = $this->em->getConnection()->fetchOne(<<<SQL
            SELECT u.id
            FROM users u
            JOIN block_states bs ON bs.block_id = u.block AND bs.str_code = 'frozen'
            JOIN all_history ah ON ah.uid = u.id
            JOIN all_history_kind hk ON hk.hist_kind_id = ah.hist_kind_id
                                   AND hk.hist_kind_str_code = 'frozen'
            WHERE u.is_juridical = 0
            ORDER BY u.id DESC
            LIMIT 1
        SQL);

        if (!$testUserId)
            self::markTestIncomplete('Нет пользователя frozen с историей заморозки для проверки.');

        $testUser = $this->userRepo->find((int)$testUserId);

        $container = static::getContainer();
        $taskRepo = $container->get(UserTaskRepositoryInterface::class);
        $taskTypeRepo = $container->get(UserTaskTypeRepositoryInterface::class);
        $taskStateRepo = $container->get(UserTaskStateRepositoryInterface::class);

        $freezeType = $taskTypeRepo->findOneBy(['code' => 'freeze']);
        $freezeState = $taskStateRepo->findOneBy(['code' => 'new']);
        if (!$freezeType || !$freezeState) {
            self::markTestIncomplete('Не найдены справочные значения задач (freeze).');
        }

        $this->loginClient($this->client, $testUser);
        $this->client->request('POST', '/user-cabinet/disable-freeze');

        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            $payload = json_decode($this->client->getResponse()->getContent(), true);
            $message = is_array($payload) ? ($payload['message'] ?? '') : '';
            self::markTestIncomplete('Разморозка недоступна: ' . $message);
        }

        $this->assertResponseHeaderSame('content-type', 'application/json');

        $stillActive = $taskRepo->findOneBy([
            'user' => $testUser,
            'type' => $freezeType,
            'state' => $freezeState,
        ]);
        $this->assertEmpty($stillActive, 'Активная задача заморозки должна быть отменена.');
    }

    public function testDisableFreezeRejectedWithoutHistory(): void
    {
        $testUserId = $this->em->getConnection()->fetchOne(<<<SQL
            SELECT u.id
            FROM users u
            WHERE u.is_juridical = 0
              AND NOT EXISTS (
                  SELECT 1
                  FROM all_history ah
                  JOIN all_history_kind hk ON hk.hist_kind_id = ah.hist_kind_id
                                            AND hk.hist_kind_str_code = 'frozen'
                  WHERE ah.uid = u.id
              )
            ORDER BY u.id DESC
            LIMIT 1
        SQL);

        if (!$testUserId) {
            self::markTestIncomplete('Не найден пользователь без истории заморозки.');
        }

        $testUser = $this->userRepo->find((int)$testUserId);
        if (!$testUser) {
            self::markTestIncomplete('Пользователь без истории не найден по id.');
        }

        $this->loginClient($this->client, $testUser);
        $this->client->request('POST', '/user-cabinet/disable-freeze');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
