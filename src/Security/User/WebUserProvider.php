<?php

namespace App\Security\User;

use App\Security\User\WebUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class WebUserProvider implements UserProviderInterface
{
	public function loadUserByUsername($username)
	{
		// make a call to your webservice here
		$userData = NULL;
		// pretend it returns an array on success, false if there is no user

		if ($userData) {
			$password = '...';

			// ...

			return new WebUser($username, $password, $salt, $roles);
		}

		throw new UsernameNotFoundException(
			sprintf('Username "%s" does not exist.', $username)
		);
	}

	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof WebUser) {
			throw new UnsupportedUserException(
				sprintf('Instances of "%s" are not supported.', get_class($user))
			);
		}

		return $this->loadUserByUsername($user->getUsername());
	}

	public function supportsClass($class)
	{
		return WebUser::class === $class;
	}
}