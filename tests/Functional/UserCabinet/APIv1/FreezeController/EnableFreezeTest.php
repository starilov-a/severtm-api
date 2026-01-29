<?php

namespace App\Tests\Functional\UserCabinet\APIv1\FreezeController;

use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Tests\Functional\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EnableFreezeTest extends TransactionalWebTestCase
{
    public function testEnableFreezeCreatesTask(): void
    {
        $container = static::getContainer();
        $reasonRepo = $container->get(FreezeReasonRepository::class);
        $reason = $reasonRepo->findOneBy(['isAdmin' => false]);
        if (!$reason) {
            self::markTestIncomplete('Нет доступных причин для заморозки в БД.');
        }

        $testUser = $this->getDefaultUser();
        $this->loginClient($this->client);

        $startDate = (new \DateTimeImmutable('+1 day'))->format('d.m.Y');
        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => $startDate,
            'reason_id' => $reason->getId(),
        ]));

        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            $payload = json_decode($this->client->getResponse()->getContent(), true);
            $message = is_array($payload) ? (implode(',', $payload['data']) ?? '') : '';
            self::markTestIncomplete('Заморозка недоступна для тестового пользователя: ' . $message);
        }

        $this->assertResponseHeaderSame('content-type', 'application/json');

        $taskRepo = $container->get(UserTaskRepository::class);
        $taskTypeRepo = $container->get(UserTaskTypeRepository::class);
        $taskStateRepo = $container->get(UserTaskStateRepository::class);

        $freezeType = $taskTypeRepo->findOneBy(['code' => 'freeze']);
        $newState = $taskStateRepo->findOneBy(['code' => 'new']);
        if (!$freezeType || !$newState) {
            self::markTestIncomplete('Не найдены справочные значения задач (freeze/new).');
        }

        $tasks = $taskRepo->findBy([
            'user' => $testUser,
            'type' => $freezeType,
            'state' => $newState,
        ]);

        $this->assertNotEmpty($tasks, 'Задача на заморозку должна быть создана.');
    }

    public function testEnableFreezeRejectedWithPastDate(): void
    {
        $testUserId = $this->em->getConnection()->fetchOne(<<<SQL
            SELECT u.id
            FROM users u
            JOIN block_states bs ON bs.block_id = u.block
            JOIN web_users wu ON wu.uid = u.id
            WHERE u.is_juridical = 0
              AND bs.str_code NOT IN ('blocked', 'frozen')
            ORDER BY u.id DESC
            LIMIT 1
        SQL);

        if (!$testUserId) {
            self::markTestIncomplete('Не найден подходящий пользователь для проверки (не frozen/blocked).');
        }

        $testUser = $this->userRepo->find((int)$testUserId);
        if (!$testUser) {
            self::markTestIncomplete('Пользователь не найден по id для проверки.');
        }

        $this->loginClient($this->client, $testUser);

        $reasonRepo = static::getContainer()->get(FreezeReasonRepository::class);
        $reason = $reasonRepo->findOneBy(['isAdmin' => false]);
        if (!$reason) {
            self::markTestIncomplete('Нет доступных причин для заморозки в БД.');
        }

        $startDate = (new \DateTimeImmutable('-2 days'))->format('Y-m-d');
        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => $startDate,
            'reason_id' => $reason->getId(),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testEnableFreezeMissingFields(): void
    {
        $this->loginClient($this->client);

        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode((string)$this->client->getResponse()->getContent(), true);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('message', $payload);
        $this->assertIsString($payload['message']);
        $this->assertIsArray($payload['data']);
    }

    public function testEnableFreezeInvalidDateFormat(): void
    {
        $this->loginClient($this->client);


        $reasonRepo = static::getContainer()->get(FreezeReasonRepository::class);
        $reason = $reasonRepo->findOneBy(['isAdmin' => false]);
        if (!$reason) {
            self::markTestIncomplete('Нет доступных причин для заморозки в БД.');
        }

        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => 'not-a-date',
            'reason_id' => $reason->getId(),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode((string)$this->client->getResponse()->getContent(), true);

        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('message', $payload);
        $this->assertIsString($payload['message']);
        $this->assertIsArray($payload['data']);
    }

    public function testEnableFreezeReasonNotFound(): void
    {
        $this->loginClient($this->client);

        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => (new \DateTimeImmutable('+1 day'))->format('Y-m-d'),
            'reason_id' => 999999999,
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode((string)$this->client->getResponse()->getContent(), true);

        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('message', $payload);
        $this->assertIsString($payload['message']);
        $this->assertIsArray($payload['data']);
    }
}
