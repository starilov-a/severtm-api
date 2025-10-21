<?php


namespace App\Modules\UserCabinet\Service;


use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserProfileDto;
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

        $dtoUser = new UserProfileDto($user);
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());

        return [
            'webUser' => [...$dtoWebUser->toArray()],
            'address' => [...$dtoAddress->toArray()],
        ];
    }

    public function getFullInfo(int $uid): array
    {
        return [];
    }
}
