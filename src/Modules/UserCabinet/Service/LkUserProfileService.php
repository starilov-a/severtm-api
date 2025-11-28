<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\UserRepository;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Domain\Repository\WebUserRepository;
use App\Modules\Common\Domain\Service\Dto\Request\WebUserDto as WebUserDtoRequest;
use App\Modules\UserCabinet\Service\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserFullInfoDto;
use App\Modules\UserCabinet\Service\Dto\Response\WebUserDto;
use Doctrine\ORM\EntityManagerInterface;

class LkUserProfileService
{
    protected UserRepository $userRepo;
    protected WebUserRepository $webUserRepo;
    protected EntityManagerInterface $em;

    public function __construct(
        UserRepository         $userRepo,
        WebUserRepository      $webUserRepo,
        EntityManagerInterface $em
    )
    {
        $this->userRepo = $userRepo;
        $this->webUserRepo = $webUserRepo;
        $this->em = $em;
    }
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

        if (!$user)
            throw new BusinessException('Пользователь не найден');


        $dtoUser = new UserDto($user);
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());

        $dto = new UserFullInfoDto($dtoUser, $dtoWebUser, $dtoAddress);

        return $dto->toArray();
    }


    public function updateUserInfo(
        WebUserDtoRequest $dto
    ): int
    {
        $webUser = $this->webUserRepo->find($dto->getUid());
        if (!$webUser)
            throw new BusinessException('Пользователь не найден');

        $webUser->setComment($dto->getComment());
        $webUser->setPhone($dto->getPhone());
        $webUser->setEmail($dto->getEmail());

        try {
            $this->em->flush();
            return $dto->getUid();
        } catch (BusinessException $e) {
            throw new BusinessException($e->getMessage());
        }
    }

    public function updateUserPassword(WebUserDtoRequest $dto): int
    {
        $webUser = $this->webUserRepo->find($dto->getUid());
        if (!$webUser)
            throw new BusinessException('Пользователь не найден');

        $pass = $this->encryptPass($dto->getPasswdHash());
        $webUser->setPasswdHash($pass);

        try {
            $this->em->flush();
            return $dto->getUid();
        } catch (BusinessException $e) {
            throw new BusinessException($e->getMessage());
        }

    }


    public function checkPassword(string $pass): void
    {
        $webUser = $this->webUserRepo->find(UserSessionService::getUserId());
        if($webUser->getPasswdHash() !== $this->encryptPass($pass)){
            throw new BusinessException("Старый пароль введен не верно!");
        }
    }

    protected function encryptPass(string $pass): string {
        return  md5($pass);
    }

}
