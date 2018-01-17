<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Http\Firewall\AccessListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


use App\Entity\Admin;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function listener() {
		// replace this line with your own code!
		$accessMap = new AccessMap();
		$requestMatcher = new RequestMatcher('^/admin');
		$accessMap->add($requestMatcher, array('ROLE_ADMIN'));

		$accessListener = new AccessListener(
		    $securityContext,
		    $accessDecisionManager,
		    $accessMap,
		    $authenticationManager
		);
	}

	public function checker() {
		$authorizationChecker = new AuthorizationChecker(
		    $tokenStorage,
		    $authenticationManager,
		    $accessDecisionManager
		);

		if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
		    throw new AccessDeniedException();
		}
	}
}
