<?php
namespace App\Security\User;

use App\Security\User\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class MainUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        // make a call to your webservice here
  			$maybeUser = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($username);
        // pretend it returns an array on success, false if there is no user

        if ($maybeUser) {
            $username = $maybeUser->getUsername();
            $password = $maybeUser->getPassword();
            $salt = $maybeUser->getSalt();
            $roles = $maybeUser->getRoles();
            return new User($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }
}
