<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use Doctrine\DBAL\Connection;
use RuntimeException;

Class PasswordHashRepository
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function generateHashes(string $password): array
    {
        $sql = <<<SQL
SELECT
    MD5(:password) AS md5_hash,
    ENCRYPT(:password, :password) AS encrypt_hash,
    HEX(AES_ENCRYPT(:password, 'hash')) AS aes_encrypt_hash
SQL;

        $row = $this->connection->fetchAssociative($sql, [
            'password' => $password,
        ]);

        if ($row === false) {
            throw new RuntimeException('Не удалось сгенерировать legacy password hashes.');
        }

        return [
            'md5Hash' => (string) ($row['md5_hash'] ?? ''),
            'encryptHash' => (string) ($row['encrypt_hash'] ?? ''),
            'aesEncryptHash' => (string) ($row['aes_encrypt_hash'] ?? ''),
        ];
    }
}