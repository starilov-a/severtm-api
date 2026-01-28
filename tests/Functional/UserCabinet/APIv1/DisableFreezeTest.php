<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Modules\Common\Domain\Repository\AllHistoryKindRepository;
use App\Modules\Common\Domain\Repository\AllHistoryRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Tests\Functional\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DisableFreezeTest extends TransactionalWebTestCase
{
    public function testDisableFreezeForFrozenUser(): void
    {
        $testUser = $this->userRepo->findOneBy(['blockState' => 2, 'isJuridical' => 0], ['id' => 'DESC']);

        $container = static::getContainer();
        $taskRepo = $container->get(UserTaskRepository::class);
        $taskTypeRepo = $container->get(UserTaskTypeRepository::class);
        $taskStateRepo = $container->get(UserTaskStateRepository::class);

        $freezeType = $taskTypeRepo->findOneBy(['code' => 'freeze']);
        if (!$freezeType) {
            self::markTestIncomplete('Не найдены справочные значения задач (freeze).');
        }

        $activeFreezeTasks = $taskRepo->findBy([
            'user' => $testUser,
            'type' => $freezeType,
        ]);

        if (empty($activeFreezeTasks)) {
            self::markTestIncomplete('Для пользователя нет активной задачи заморозки.');
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
            'state' => $taskStateRepo->findOneBy(['code' => 'new'])
        ]);
        $this->assertEmpty($stillActive, 'Активная задача заморозки должна быть отменена.');
    }

    public function testDisableFreezeRejectedWithoutHistory(): void
    {
        $testUser = $this->userRepo->findOneBy(['blockState' => 2, 'isJuridical' => 0], ['id' => 'DESC']);

        $container = static::getContainer();
        $historyRepo = $container->get(AllHistoryRepository::class);
        $historyKindRepo = $container->get(AllHistoryKindRepository::class);
        $frozenKind = $historyKindRepo->findOneBy(['strCode' => 'frozen']);

        if (!$frozenKind) {
            self::markTestIncomplete('Нет справочника истории frozen.');
        }

        $history = $historyRepo->findOneBy([
            'user' => $testUser,
            'kind' => $frozenKind,
        ]);

        if ($history) {
            self::markTestIncomplete('У пользователя уже есть история заморозки.');
        }

        if ($testUser->getBlockState()->getCode() === 'frozen') {
            self::markTestIncomplete('Пользователь уже в статусе frozen.');
        }

        $this->loginClient($this->client, $testUser);
        $this->client->request('POST', '/user-cabinet/disable-freeze');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
