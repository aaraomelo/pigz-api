<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomAuthenticator extends JWTAuthenticator
{
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return;
        }
        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // TODO: Implement getUser() method.
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    // public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    // {
    //     // TODO: Implement onAuthenticationFailure() method.
    // }

    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    // {
    //     // TODO: Implement onAuthenticationSuccess() method.
    // }

    public function supportsRememberMe()
    {
        return false;
    }

    // public function start(Request $request, AuthenticationException $authException = null)
    // {
    //     // TODO: Implement start() method.
    // }
}