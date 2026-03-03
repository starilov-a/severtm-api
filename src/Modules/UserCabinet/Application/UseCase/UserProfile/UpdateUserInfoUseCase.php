<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebUserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\WebUserDto as WebUserRequestDto;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUserInfoUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepositoryInterface $userRepo,
        protected WebUserRepositoryInterface $webUserRepo,
    ) {}

    public function handle(int $uid, WebUserRequestDto $dto): int
    {
        $user = $this->userRepo->find($uid);
        $webUser = $this->webUserRepo->find($user->getId());

        $webUser->setComment($dto->getComment());
        $webUser->setPhone($dto->getPhone());
        $webUser->setEmail($dto->getEmail());

        $this->em->flush();

        return $dto->getUid();
    }
}
