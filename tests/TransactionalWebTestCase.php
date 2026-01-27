<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TransactionalWebTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $em = null;
    protected static string $testLogin;
    protected static string $testPassword;

    protected static string $testUserId;

    protected static $testModeId;


    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function startTransaction(): void
    {
        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->em->getConnection()->beginTransaction();
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

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $projectDir = getenv('PWD') ?: getcwd(); 

        $envFile = $projectDir . '/.env.test';

        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') {
                    continue;
                }
                putenv($line);
            }
        }

        self::$testLogin    = getenv('TEST_LOGIN') ?: throw new \RuntimeException('TEST_LOGIN not set');
        self::$testPassword = getenv('TEST_PASSWORD') ?: throw new \RuntimeException('TEST_PASSWORD not set');
        self::$testUserId   = getenv('TEST_USER_ID') ?: throw new \RuntimeException('TEST_USER_ID not set in .env.test');
        self::$testModeId   = getenv('TEST_MODE_ID') ?: throw new \RuntimeException('TEST_MODE_ID not set in .env.test');
    }

    protected function getTestUserId():string      
    {
        return self::$testUserId;
    }

    protected function getTestModeId(){
        return self::$testModeId;
    }

    public function loginClient($client): void
    {
        $client->request('POST', '/user-cabinet/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'login' => getenv('TEST_LOGIN'),
            'password' => getenv('TEST_PASSWORD'),
        ]));
    }
}