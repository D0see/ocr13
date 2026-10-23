<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class ApiLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly AuthenticationSuccessHandlerInterface $jwtSuccessHandler,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        if (!in_array('ROLE_API', $token->getRoleNames(), true)) {
            return new JsonResponse(
                ['message' => 'API access is not enabled.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $this->jwtSuccessHandler->onAuthenticationSuccess($request, $token);
    }
}
