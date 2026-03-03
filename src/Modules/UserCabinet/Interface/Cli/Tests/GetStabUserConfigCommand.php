<?php

namespace App\Modules\UserCabinet\Interface\Cli\Tests;

use App\Modules\Common\Application\UseCase\TestUser\CommandGetStabTestUserUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

// LEGACY - можно удалять либо переиспользовать
#[AsCommand(
    name: 'common:test:create-user-stabs',
    description: 'Получение стабов для существующих пользователей',
)]
class GetStabUserConfigCommand extends Command
{
    public function __construct(
        protected KernelInterface $kernel,
        protected EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fileSystem = new Filesystem();
        $path = "{$this->kernel->getProjectDir()}/var/tests/user.stab.json"; // Использование %kernel.project_dir%

        try {

            $sql = 'SELECT regions_UTM.f_get_passwd_hash_encrypt(wu.passwd_hash_encrypt)
                FROM web_users wu WHERE wu.uid = :uid';

            $password =  (string) $this->em->getConnection()->fetchOne($sql, [
                'uid' => '21172034',
            ]);
            //$jsonString = $this->useCase->handle();
            //$fileSystem->dumpFile($path, $jsonString);
        } catch (\InvalidArgumentException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success($password);

        return Command::SUCCESS;
    }
}