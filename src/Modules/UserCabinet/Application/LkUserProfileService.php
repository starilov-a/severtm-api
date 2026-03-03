<?php

namespace App\Modules\UserCabinet\Application;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebUserRepository;
use App\Modules\Common\Domain\Service\Dto\Request\WebUserDto as WebUserRequestDto;
use App\Modules\Common\Domain\Service\FreezeService;
use App\Modules\UserCabinet\Application\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Application\Dto\Response\UserDto;
use App\Modules\UserCabinet\Application\Dto\Response\UserFullInfoDto;
use App\Modules\UserCabinet\Application\Dto\Response\WebUserDto;
use App\Modules\UserCabinet\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use Doctrine\ORM\EntityManagerInterface;

class LkUserProfileService
{
    public function __construct(
        protected EntityManagerInterface    $em,

        protected FreezeService             $freezeService,

        protected UserRepository            $userRepo,
        protected WebUserRepository         $webUserRepo,
    ) {}
    public function getShortUserInfo(int $uid): array
    {
        $user = $this->userRepo->find($uid);

        $dtoWebUser = new WebUserDto($user->getWebUser());

        return [
            'webUser' => [...$dtoWebUser->toArray()],
        ];
    }

    public function getFullUserInfo(int $uid): array
    {
        $user = $this->userRepo->find($uid);

        $dtoUser = new UserDto($user);
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());

        $dto = new UserFullInfoDto($dtoUser, $dtoWebUser, $dtoAddress);

        return $dto->toArray();
    }


    public function updateUserInfo(int $uid, WebUserRequestDto $dto): int
    {
        //TODO нужно будет перенести в common
        $user = $this->userRepo->find($uid);

        $webUser = $this->webUserRepo->find($user->getId());

        $webUser->setComment($dto->getComment());
        $webUser->setPhone($dto->getPhone());
        $webUser->setEmail($dto->getEmail());

        $this->em->flush();
        return $dto->getUid();
    }

    public function updateUserPassword($uid, WebUserRequestDto $dto): int
    {
        //TODO нужно будет перенести в common
        $webUser = $this->webUserRepo->find($uid);

        $pass = md5($dto->getPasswdHash());
        $webUser->setPasswdHash($pass);

        $this->em->flush();
        return $dto->getUid();

    }

    public function checkPassword(string $pass): void
    {
        //TODO нужно будет перенести в common
        $webUser = $this->webUserRepo->find(UserSessionService::getUserId());
        if($webUser->getPasswdHash() !== md5($pass)){
            throw new BusinessException("Старый пароль введен не верно!");
        }
    }
}
