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
            throw new BusinessException($uid);


        $dtoUser = new UserProfileDto($user);
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());
        dd($dtoAddress);
//        return [
//            'id' => $user->getId(),
//            'login' => $user->getLogin(),
//            'email' => $user->getEmail()
//        ];
    }
}
