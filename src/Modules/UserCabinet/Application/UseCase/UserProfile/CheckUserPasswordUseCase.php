<?php

namespace App\Modules\UserCabinet\Application\UseCase\UserProfile;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebUserRepositoryInterface;

class CheckUserPasswordUseCase
{
    public function __construct(
        protected WebUserRepositoryInterface $webUserRepo,
    ) {}

    public function handle(int $uid, string $pass): void
    {
        $webUser = $this->webUserRepo->find($uid);
        if ($webUser->getPasswdHash() !== md5($pass)) {
            throw new BusinessException('Старый пароль введен не верно!');
        }
    }
}
