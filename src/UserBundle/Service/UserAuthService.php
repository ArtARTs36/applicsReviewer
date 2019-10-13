<?php

namespace UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\User;

class UserAuthService
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param User $user
     */
    public function userAuth($user)
    {
        $unauthenticatedToken = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'user'
        );

        $authenticatedToken = $this
            ->authenticationManager
            ->authenticate($unauthenticatedToken);

        $this->tokenStorage->setToken($authenticatedToken);
    }

    public function __construct(
        Container $container,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->container = $container;
        $this->authenticationManager = $authenticationManager;
        $this->tokenStorage = $tokenStorage;
    }
}
