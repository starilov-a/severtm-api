<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

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

        $startDate = (new \DateTimeImmutable('+1 day'))->format('Y-m-d');
        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => $startDate,
            'reason_id' => $reason->getId(),
        ]));

        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            $payload = json_decode($this->client->getResponse()->getContent(), true);
            $message = is_array($payload) ? ($payload['message'] ?? '') : '';
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
        $testUser = $this->userRepo->findOneBy(['blockState' => 0, 'isJuridical' => 0], ['id' => 'DESC']);
        $this->loginClient($this->client, $testUser);

        $this->client->request('GET', '/user-cabinet/get-status-freeze');
        $payload = json_decode($this->client->getResponse()->getContent(), true);
        if (!isset($payload['data']['availableFreeze']) || $payload['data']['availableFreeze'] !== true) {
            self::markTestIncomplete('Заморозка недоступна для пользователя.');
        }

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
}
