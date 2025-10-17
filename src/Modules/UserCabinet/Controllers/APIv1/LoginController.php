<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Exception\AuthException;
use App\Modules\Common\Infrastructure\Service\Auth\Dto\SessionDto;
use App\Modules\Common\Infrastructure\Service\Auth\Service\Auth;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Entity\WebUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Modules\Common\Infrastructure\Exception;

final class LoginController extends Controller
{
    public function authenticate(): bool
    {
        return false;
    }

    /**
     * @throws \Exception
     */
    #[Route('/login', name: 'app_login_post', methods: ['POST'], format: 'json')]
    public function login(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $data = $request->toArray();
        $user = $em->getRepository(WebUser::class)->findOneBy(
            [
                'login' => $data['login'],
                'passwd_hash' => md5($data['password'])
            ]
        );
        if ($user) {
            $userDto = new SessionDto(true, $user->getUid(), $user->getUser()->getFullName(), [], [], $user->getUser()->getDistrict(), []);
            (new Auth)->login($userDto);
            return $this->responseData("UserSessionService::getSid()");
        } else {
            throw new AuthException('User not found', 403);
        }
    }


    #[Route('/logout', name: 'app_logout_post', methods: ['POST'], format: 'json')]
    public function logout(Request $request): JsonResponse
    {
        UserSessionService::logOut();
        return $this->json('User logout');
    }

}
