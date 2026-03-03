<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\UserCabinet\Application\Dto\Response\WebUserDto;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;

class GetShortUserInfoUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
    ) {}

    public function handle(int $uid): array
    {
        $user = $this->userRepo->find($uid);

        $dtoWebUser = new WebUserDto($user->getWebUser());

        return [
            'webUser' => [...$dtoWebUser->toArray()],
        ];
    }
}
