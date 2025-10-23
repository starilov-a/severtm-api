<?php


namespace App\Modules\UserCabinet\Service;


use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserFullInfoDto;
use App\Modules\UserCabinet\Service\Dto\Response\WebUserDto;

class UserProfileService
{
    protected UserRepository $userRepo;

    public function __construct(
        UserRepository $userRepo
    )
    {
        $this->userRepo = $userRepo;
    }

    public function getShortUserInfo(int $uid): array
    {
        $user = $this->userRepo->find($uid);

        if (!$user)
            throw new BusinessException('Пользователь не найден');

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
}
