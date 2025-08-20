<?php


namespace App\Modules\UserCabinet\Service;


use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Exception\UserNotFoundException;

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
            throw new UserNotFoundException($uid);

        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'email' => $user->getEmail()
        ];
    }
}