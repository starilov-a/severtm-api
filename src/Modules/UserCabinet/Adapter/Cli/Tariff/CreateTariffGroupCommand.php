<?php

namespace App\Modules\UserCabinet\Adapter\Cli\Tariff;

use App\Modules\UserCabinet\Application\UseCase\TariffGroup\CommandCreateTariffGroupUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'common:tariff-group:create',
    description: 'Создание группы тарифов',
)]
class CreateTariffGroupCommand extends Command
{
    public function __construct(
        protected CommandCreateTariffGroupUseCase $createTariffGroupUseCase,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'Уникальный код группы')
            ->addArgument('name', InputArgument::REQUIRED , 'Имя группы')
            ->addOption('user-visible', null, InputOption::VALUE_OPTIONAL, 'Видимость для пользователя (0|1)', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $code = (string) $input->getArgument('code');
        $name = (string) $input->getArgument('name');
        $userVisible = (int) $input->getOption('user-visible');

        try {
            $group = $this->createTariffGroupUseCase->handle(
                $code,
                is_string($name) ? $name : null,
                (bool)$userVisible
            );
        } catch (\InvalidArgumentException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf(
            'Тариф создан id=%d code=%s user_visible=%d',
            $group->getId(),
            (string) $group->getCode(),
            $group->getUserVisible()
        ));

        return Command::SUCCESS;
    }
}
