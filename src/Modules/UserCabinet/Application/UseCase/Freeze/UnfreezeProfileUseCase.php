<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UnfreezeProfileUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepositoryInterface $userRepo,
        protected UnfreezeInternetNoJuridicalUserUseCase $unfreezeInternetNoJuridicalUserUseCase,
    ) {}

    public function handle(int $uid): bool
    {
        return $this->em->getConnection()->transactional(function () use ($uid) {
            $user = $this->userRepo->find($uid);

            $this->unfreezeInternetNoJuridicalUserUseCase->handle($user);

            return true;
        });
    }
}
