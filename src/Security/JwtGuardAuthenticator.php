<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtGuardAuthenticator extends JWTAuthenticator
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
        try {
            $data = $this->getJwtManager()->decode($credentials);
            $username = $data['username'];
            return $userProvider->loadUserByIdentifier($username);
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }
    // public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    // {
    //     // TODO: Implement onAuthenticationFailure() method.
    // }

    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    // {
    //     // TODO: Implement onAuthenticationSuccess() method.
    // }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    // public function start(Request $request, AuthenticationException $authException = null)
    // {
    //     // TODO: Implement start() method.
    // }
}
