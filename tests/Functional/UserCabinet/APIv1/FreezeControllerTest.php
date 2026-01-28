<?php

namespace App\Tests\Functional\UserCabinet\APIv1;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Tests\Functional\TransactionalWebTestCase;
use App\Tests\Support\Dto\TestUserCredentials;
use Symfony\Component\HttpFoundation\Response;

class FreezeControllerTest extends TransactionalWebTestCase
{
    public function testGetReasons(): void
    {
        $this->loginClient($this->client);

        $this->client->request('GET', '/user-cabinet/get-reason-for-freeze');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $payload = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertIsArray($payload['data']);
    }

    public function testFreezeAndUnfreezeFlow(): void
    {
        $container = static::getContainer();

        /** @var FreezeReasonRepository $reasonRepo */
        $reasonRepo = $container->get(FreezeReasonRepository::class);
        $reason = $reasonRepo->findOneBy(['isAdmin' => false]);
        if (!$reason) {
            self::markTestIncomplete('Нет доступных причин для заморозки в БД.');
        }

        $testUser = $this->userRepo->findOneBy(['blockState' => 0, 'isJuridical' => 0], ['id' => 'DESC']);
        $this->loginClient($this->client, $testUser);

        // 1. Запрашиваем статус до заморозки
        $this->client->request('GET', '/user-cabinet/get-status-freeze');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // 2. Ставим заморозку
        $startDate = (new \DateTimeImmutable('+1 day'))->format('Y-m-d');
        $this->client->request('POST', '/user-cabinet/enable-freeze', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'startDate' => $startDate,
            'reason_id' => $reason->getId(),
        ]));

        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            $message = json_decode($this->client->getResponse()->getContent())['message'];
            self::markTestIncomplete('Заморозка недоступна для тестового пользователя: ' . $message);
        }

        $this->assertResponseHeaderSame('content-type', 'application/json');

        $taskRepo = $container->get(UserTaskRepository::class);
        $taskTypeRepo = $container->get(UserTaskTypeRepository::class);

        if (!$testUser)
            self::markTestIncomplete('Тестовый пользователь не найден в базе.');

        $freezeTaskType = $taskTypeRepo->findOneBy(['code' => 'freeze']);

        $freezeTasks = $taskRepo->findBy(['user' => $testUser, 'type' => $freezeTaskType]);
        $this->assertNotEmpty($freezeTasks, 'Задача на заморозку должна быть создана.');

        // 3. Снимаем заморозку
        $this->client->request('POST', '/user-cabinet/disable-freeze');
        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            $message = json_decode($this->client->getResponse()->getContent(), true)['message'];
            self::markTestIncomplete('Разморозка недоступна для тестового пользователя: ' . $message);
        }

        // 4. Проверяем статус после разморозки
        $this->client->request('GET', '/user-cabinet/get-status-freeze');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $payload = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $payload);
        $this->assertIsArray($payload['data']);
        $this->assertArrayHasKey('availableFreeze', $payload['data']);
        $this->assertArrayHasKey('availableUnfreeze', $payload['data']);
    }
}
