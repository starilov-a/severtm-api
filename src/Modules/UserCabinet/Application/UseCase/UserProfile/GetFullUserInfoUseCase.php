<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Application\Dto\Response\AddressDto;
use App\Modules\UserCabinet\Application\Dto\Response\UserDto;
use App\Modules\UserCabinet\Application\Dto\Response\UserFullInfoDto;
use App\Modules\UserCabinet\Application\Dto\Response\WebUserDto;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;

class GetFullUserInfoUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
    ) {}

    public function handle(int $uid): array
    {
        /** @var $user User */
        $user = $this->userRepo->find($uid);

        $dtoUser = new UserDto($user);
        $dtoWebUser = new WebUserDto($user->getWebUser());
        $dtoAddress = new AddressDto($user->getAddress());

        $dto = new UserFullInfoDto($dtoUser, $dtoWebUser, $dtoAddress);

        return $dto->toArray();
    }
}
