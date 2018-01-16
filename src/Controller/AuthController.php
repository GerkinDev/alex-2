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
use App\Service\Mailer;
use App\Service\UriTokenHandler;

const USER_FIREWALL = 'main';

class AuthController extends Controller
{
	/**
	* @Route("/login", name="login")
	*/
	public function login(
		Request $request,
		AuthenticationUtils $authUtils,
		AuthorizationCheckerInterface $authChecker
	):Response {
		//$mailer->sendMailNewUser($this->getUser());
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}

		return $this->render('pages/login.html.twig', array(
			'last_username' => $authUtils->getLastUsername(),
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

		return $this->render('pages/signup.html.twig');
	}

	/**
	* @Route("/dosignup", name="signup_check")
	*/
	public function signupCheck(
		Request $request,
		UserPasswordEncoderInterface $encoder,
		ValidatorInterface $validator,
		AuthorizationCheckerInterface $authChecker,
		Mailer $mailer
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

		$session = new Session();
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

			$mailer->sendMailNewUser($user);
			$session->getFlashBag()->add('info', 'Registration done! You\'ll be able to log in once your email is verified. Watch your mailbox!');
		} catch (AuthException $exception) {
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
		}
		return $this->redirectToRoute('index');
	}

	/**
	 * @Route("/validate_account/{token}", name="validate_account")
	 */
	public function validateAccount(
		Request $request,
		UriTokenHandler $tokenHandler
	):Response {
		$token = rawurldecode($request->get('token'));
		if(strlen($token) !== 24){
			throw $this->createNotFoundException('Invalid token provided');
		}
		$id = $tokenHandler->decryptRouteToken($token, 'validateAccount');
		if(!is_numeric($id)){
			throw $this->createNotFoundException('Invalid token provided');
		}
		$id = intval($id);
		$user = $this->getDoctrine()
			->getRepository(User::class)
			->find($id);
		if(!$user instanceof User){
			throw $this->createNotFoundException('Invalid token provided');
		}
		if($user->getActive() === true){
			throw $this->createNotFoundException('Token already used');
		}
		// Enable the user
		$user->setActive(true);

		// Save in DB
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		$session = new Session();
		$session->getFlashBag()->add('info', 'Validation done. You are now logged in.');
		$this->doLoginUser($user, $request);
		return $this->redirectToRoute('index');
	}

	/**
	 * @Route("/password_lost", name="password_lost")
	 */
	public function passwordLost(
		Request $request,
		AuthorizationCheckerInterface $authChecker,
		UriTokenHandler $tokenHandler,
		Mailer $mailer
	):Response {
		// If user is already logged in, redirect him to homepage
		if( $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			return $this->redirectToRoute('index');
		}
		$session = new Session();
		$username = $request->request->get('_username');

		if($username === ''){
			$session->getFlashBag()->add('login', 'You must provide an email.');
			return $this->redirectToRoute('login');
		}

		/** @var User $user */
		$user = $this
			->getDoctrine()
			->getRepository(User::class)
			->findOneByEmail($username);
		if(!$user instanceof User){
			$session->getFlashBag()->add('login', 'Unknown user');
			return $this->redirectToRoute('login');
		}

		$user->resetPasswordGenerateToken($tokenHandler);

		// Save in DB
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		$mailer->sendMailUserResetPassword($user);
		$session->getFlashBag()->add('info', 'A mail have been sent. Check your mails to change your password.');
		return $this->redirectToRoute('login');
	}

	/**
	 * @Route("/reset_password/{token}", name="reset_password")
	 */
	public function resetPassword(
		Request $request,
		UserPasswordEncoderInterface $encoder,
		UriTokenHandler $tokenHandler
	):Response {
		$token = rawurldecode($request->get('token'));
		if(strlen($token) !== 24){
			throw $this->createNotFoundException('Invalid token provided');
		}
		$user = $this->getDoctrine()
			->getRepository(User::class)
			->findOneByPasswordResetToken($token);
		if(!$user instanceof User){
			throw $this->createNotFoundException('Invalid token provided');
		}

		// Check if form is posted
		$reqContent = $request->request;
		if($request->getMethod() === 'POST'){
			$newPassword = $reqContent->get('password');
			$repeatPassword = $reqContent->get('repeat_password');
			$token = $reqContent->get('token');

			$session = new Session();

			try{
				if( $newPassword === '' || $repeatPassword === '' || $token === '' ){
					throw new AuthException('Form is incomplete');
				}
				if($newPassword !== $repeatPassword){
					throw new AuthException('Passwords mismatched');
				}
				$user->setRawPassword($newPassword, $encoder);

				// Save in DB
				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();
				$session->getFlashBag()->add('info', 'Password reset. You may now log in.');
				return $this->redirectToRoute('login');
			} catch(AuthException $exception) {
				$session->getFlashBag()->add('login', $exception->getMessage());
				return $this->render('pages/change-password.html.twig', array(
					'user' => $user,
					'token' => $token
				));
			}
		} else {
			return $this->render('pages/change-password.html.twig', array(
				'user' => $user,
				'token' => $token
			));
		}
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
