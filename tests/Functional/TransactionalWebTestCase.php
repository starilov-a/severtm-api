<?php

namespace App\Tests\Functional;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Tests\Support\Dto\TestUserCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TransactionalWebTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $em = null;
    protected ?KernelBrowser $client = null;

    protected ?UserRepository $userRepo = null;

    protected ?TestUserCredentials $defaultUser = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->startTransaction($this->client);

        $this->userRepo = static::getContainer()->get(UserRepository::class);
    }

    protected function startTransaction(?KernelBrowser $client = null): void
    {
        $client ??= $this->client;
        if ($client) {
            $client->disableReboot();
        }

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        if (!$this->em->getConnection()->isTransactionActive()) {
            $this->em->getConnection()->beginTransaction();
        }
    }

    protected function tearDown(): void
    {
        if ($this->em !== null) {
            $conn = $this->em->getConnection();
            if ($conn->isTransactionActive()) {
                $conn->rollBack();
            }
            $this->em->close();
        }

        parent::tearDown();
    }

    public function loginClient(KernelBrowser $client, User $testUser = null): void
    {
        //Дефолтный пользак
        if (!$testUser)
            $testUser = $this->userRepo->findOneBy(['blockState' => 0, 'isJuridical' => 0], ['id' => 'DESC']);

        $creds = $this->createCredentials($testUser);

        $client->disableReboot();
        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => $creds->login,
            'password' => $creds->password,
        ]));
    }

    protected function createCredentials($user): TestUserCredentials
    {
        $sql = 'SELECT regions_UTM.f_get_passwd_hash_encrypt(wu.passwd_hash_encrypt)
                FROM web_users wu WHERE wu.uid = :uid';

        $password =  (string) $this->em->getConnection()->fetchOne($sql, [
            'uid' => $user->getId(),
        ]);

        return new TestUserCredentials(
            $user->getId(),
            $user->getLogin(),
            $password
        );
    }
}