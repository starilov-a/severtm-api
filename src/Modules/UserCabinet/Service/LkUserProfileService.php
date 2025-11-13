<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserProfileDto;
use App\Modules\UserCabinet\Service\Dto\Response\WebUserDto;

class LkUserProfileService
{
    public function __construct(
        protected UserRepository $userRepo
    ){}
    public function getShortUserInfo(int $uid): array
    {
        $user = $this->userRepo->find($uid);

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