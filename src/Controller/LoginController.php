<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use App\Exception\AuthException;

const USER_FIREWALL = 'main';

class LoginController extends Controller
{
	/**
	* @Route("/login", name="login")
	*/
	public function login(
		Request $request,
		AuthenticationUtils $authUtils,
		AuthorizationCheckerInterface $authChecker
	):Response {
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}

		// last username entered by the user
		$lastUsername = $authUtils->getLastUsername();

		return $this->render('login.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}

	/**
	* @Route("/dologin", name="login_check")
	*/
	public function loginCheck(
		Request $request,
		AuthenticationUtils $authUtils,
		AuthorizationCheckerInterface $authChecker
	):Response {
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}

		// User is still not logged in... Check for errors
		$error = $authUtils->getLastAuthenticationError();
		if($error){
			$session = new Session();
			$session->getFlashBag()->add('login', $error->getMessage());
		}
		return $this->redirectToRoute('login');
	}

	/**
	* @Route("/signup", name="signup")
	*/
	public function signup(
		Request $request,
		AuthorizationCheckerInterface $authChecker
	):Response {
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}

		return $this->render('signup.html.twig');
	}

	/**
	* @Route("/dosignup", name="signup_check")
	*/
	public function signupCheck(
		Request $request,
		UserPasswordEncoderInterface $encoder,
		ValidatorInterface $validator,
		AuthorizationCheckerInterface $authChecker
	):Response {
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}

		$query = $request->request;
		$fname = $query->get('fname');
		$lname = $query->get('lname');
		$password = $query->get('password');
		$repeat_password = $query->get('repeat_password');
		$email = $query->get('email');
		try {
			if ($fname === '' || $lname === '' || $password === '' || $repeat_password === '' || $email === '') {
				throw new AuthException('Missing Values !');
			}
			if ($password !== $repeat_password) {
				throw new AuthException('Passwords mismatched !');
			}
			if (strlen($password) < 8) {
				throw new AuthException('Password is too short');
			}

			$em = $this->getDoctrine()->getManager();

			$user = new User();
			$user
				->setFirstName($fname)
				->setLastName($lname)
				->setRawPassword($password, $encoder)
				->setEmail($email);

			$errors = $validator->validate($user);
			if (count($errors) > 0) {
				foreach ($errors as $error) {
					throw new AuthException($error->getMessage());
				}
			}

			// tell Doctrine you want to (eventually) save the Product (no queries yet)
			$em->persist($user);

			// actually executes the queries (i.e. the INSERT query)
			try {
				$em->flush();
			} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $exception) {
				throw new AuthException('This email is already taken');
			}

			$this->doLoginUser($user, $request);
		} catch (AuthException $exception) {
			$session = new Session();
			$session->set('signup_data', array(
				'fname' => $fname,
				'lname' => $lname,
				'password' => $password,
				'repeat_password' => $repeat_password,
				'email' => $email,
			));

			// add flash messages
			$session->getFlashBag()->add('login', $exception->getMessage());
			return $this->redirectToRoute('signup');
		} catch (\Exception $exception) {
			return $this->render('error.html.twig');
		}
		return $this->redirectToRoute('index');
	}

	private function doLoginUser(User $user, Request $request) {
		//Handle getting or creating the user entity likely with a posted form
		// The third parameter "main" can change according to the name of your firewall in security.yml
		$token = new UsernamePasswordToken($user, null, USER_FIREWALL, $user->getRoles());
		$this->get('security.token_storage')->setToken($token);

		// If the firewall name is not main, then the set value would be instead:
		// $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
		$this->get('session')->set('_security_'.USER_FIREWALL, serialize($token));

		// Fire the login event manually
		$event = new InteractiveLoginEvent($request, $token);
		$this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
	}
}
