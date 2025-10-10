<?php


namespace App\Modules\UserCabinet\Service;


use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Repository\UserRepository;

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

        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'email' => $user->getEmail()
        ];
    }
}