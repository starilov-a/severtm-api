<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\CustomController\Auth;
use App\Modules\UserCabinet\Controllers\CustomController\UserSession;
use App\Modules\UserCabinet\Entity\WebUser;
use App\Modules\UserCabinet\Service\Dto\Session\SessionDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
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
        $session = $request->getSession();

        if ($session->has('loggedIn')) {
            return $this->json('User already logged in');
        }


        $data = $request->toArray();
        $user = $em->getRepository(WebUser::class)->findOneBy(
            [
                'login' => $data['login'],
                'passwd_hash' => md5($data['password'])
            ]
        );
        if ($user) {
            $userDto = new SessionDto(true, $user->getUid(), $user->getUser()->getFullName(), [], [], $user->getUser()->getDistrict(), []);
            (new Auth)->login($userDto, $session);
//            $res = [$user->getUser()->getFullName()];
            return $this->json($session->getId());
        } else {
            throw new \Exception('User not found', 403);
        }


    }


    #[Route('/logout', name: 'app_logout_post', methods: ['POST'], format: 'json')]
    public function logout(Request $request): JsonResponse
    {
        $session = $request->getSession();
        UserSession::logOut($session);
        $res = ['User logout'];
        return $this->json($res);
    }

}
