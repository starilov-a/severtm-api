<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\UserCabinet\Domain\Repository\WebUserRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\WebUserDto as WebUserRequestDto;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUserPasswordUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected WebUserRepository $webUserRepo,
    ) {}

    public function handle(int $uid, WebUserRequestDto $dto): int
    {
        $webUser = $this->webUserRepo->find($uid);

        $pass = md5($dto->getPasswdHash());
        $webUser->setPasswdHash($pass);

        $this->em->flush();

        return $dto->getUid();
    }
}
