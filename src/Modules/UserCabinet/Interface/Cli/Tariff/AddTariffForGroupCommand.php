<?php

namespace App\Modules\UserCabinet\Interface\Cli\Tariff;

use App\Modules\UserCabinet\Application\UseCase\TariffGroup\CommandLinkTariffForGroupUseCase;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffGroupRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'common:tariff-group:add-tariff-for-group',
    description: 'Добавление тарифа к группе',
)]
class AddTariffForGroupCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface        $em,

        protected CommandLinkTariffForGroupUseCase  $commandLinkTariffForGroupUseCase,
        protected TariffGroupRepositoryInterface             $tariffGroupRepo,
        protected ProdServModeRepositoryInterface            $prodServModeRepo,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('tariff-group-code', InputArgument::REQUIRED, 'Код группы тарифов')
            ->addArgument('tariff-code', InputArgument::REQUIRED , 'Код тарифа');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $groupCode = (string) $input->getArgument('tariff-group-code');
        $tariffCode = (string) $input->getArgument('tariff-code');

        try {

            $this->em->getConnection()->transactional(function () use (
                $groupCode,
                $tariffCode,
            ) {
                $tariffGroup = $this->tariffGroupRepo->findOneBy(['code' => $groupCode]);
                $tariff = $this->prodServModeRepo->findOneByStrCode($tariffCode)->getTariff();

                $this->commandLinkTariffForGroupUseCase->handle($tariff, $tariffGroup);

                return true;
            });
        } catch (\InvalidArgumentException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf(
            'Тариф %s добавлен в группу %s',
            $tariff->getName(),
            $tariffGroup->getName(),
        ));

        return Command::SUCCESS;
    }
}
