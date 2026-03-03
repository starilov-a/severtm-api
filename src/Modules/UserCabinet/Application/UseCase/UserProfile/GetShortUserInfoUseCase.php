<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\UserCabinet\Application\Dto\Response\WebUserDto;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;

class GetShortUserInfoUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
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
