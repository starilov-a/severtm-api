<?php


namespace App\Modules\UserCabinet\Service;


use App\Modules\Common\Infrastructure\Exception\UserNotFoundException;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Service\Dto\Response\UserProfileDto;
use App\Modules\UserCabinet\Service\Dto\Response\WebUserDto;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class UserProfileService
{
    protected UserRepository $userRepo;

    public function __construct(
        UserRepository $userRepo
    )
    {
        $this->userRepo = $userRepo;
    }

    public function getShortUserInfo(int $uid)
    {
        $user = $this->userRepo->find($uid);
        if (!$user) {
            throw new Exception("User is not found", Response::HTTP_UNAUTHORIZED);
        }
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());

        return [
            'webUser' => [...$dtoWebUser->toArray()],
            'address' => [...$dtoAddress->toArray()],
        ];
    }
}
