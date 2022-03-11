<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private RouterInterface $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'security_login' && $request->isMethod('POST');
    }
    public function authenticate(Request $request): Passport
    {
        //Récupère la requète POST 
        // try {
        //     $credentials = $request->request->get('login');
        //     return new Passport(new UserBadge($credentials['email']), new PasswordCredentials($credentials['password']));
        // } catch (UserNotFoundException $e) {
        //     throw new AuthenticationException("Cette adresse email n'est pas connue");
        // }
        try {
            $login = $request->request->get('login');
            $email = $login['email'];
            $password = $login['password'];
            return new Passport(
                new UserBadge($email, function ($userIdentifier) {
                    // optionally pass a callback to load the User manually
                    $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                    if (!$user) {
                        throw new UserNotFoundException("Cette adresse email n'est pas connue");
                    }
                    return $user;
                }),
                new PasswordCredentials($password)
            );
        } catch (UserNotFoundException $e) {
            dd('here');
            throw new AuthenticationException("Cette adresse email n'est pas connue");
        }
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->router->generate('homepage')
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
        return new RedirectResponse(
            $this->router->generate('security_login')
        );
    }

    public function start() {
        return new RedirectResponse('/login');
    }
}
