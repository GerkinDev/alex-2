<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;

const USER_FIREWALL = 'main';

class LoginController extends Controller
{
	/**
     * @Route("/login", name="login")
     */
	public function login(Request $request, AuthenticationUtils $authUtils)
	{
		// get the login error if there is one
		$error = $authUtils->getLastAuthenticationError();

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
	public function loginCheck()
	{
		// replace this line with your own code!
		return $this->render('@Maker/demoPage.html.twig', [ 'path' => str_replace($this->getParameter('kernel.project_dir').'/', '', __FILE__) ]);
	}

	/**

		 * @Route("/signup", name="signup")
		 */
	public function signup(Request $request, AuthenticationUtils $authUtils)
	{
		// get the login error if there is one
		$error = $authUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authUtils->getLastUsername();

		return $this->render('signup.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}

	/**
	* @Route("/dosignup", name="signup_check")
	*/
	public function signupCheck(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$query = $request->request;

		if(!($query->has('fname') && $query->has('lname') && $query->has('password') && $query->has('repeat_password') && $query->has('email'))){
			throw new \Exception();
		}
		if($query->get('password') !== $query->get('repeat_password')){
			throw new \Exception();
		}

		$em = $this->getDoctrine()->getManager();

		$user = new User();
		$user
			->setFirstName($query->get('fname'))
			->setLastName($query->get('lname'))
			->setPassword($encoder->encodePassword($user, $query->get('password')))
			->setEmail($query->get('email'));

		// tell Doctrine you want to (eventually) save the Product (no queries yet)
		$em->persist($user);

		// actually executes the queries (i.e. the INSERT query)
		try{
			$em->flush();
		} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $exception){
			throw new \Exception('This email is already taken');
		}

		$this->doLoginUser($user, $request);

		// replace this line with your own code!
		return $this->render('@Maker/demoPage.html.twig', [ 'path' => str_replace($this->getParameter('kernel.project_dir').'/', '', __FILE__) ]);
	}

	private function doLoginUser(User $user, Request $request){
		//Handle getting or creating the user entity likely with a posted form
		// The third parameter "main" can change according to the name of your firewall in security.yml
		$token = new UsernamePasswordToken($user, null, USER_FIREWALL, $user->getRoles());
		$this->get('security.token_storage')->setToken($token);

		// If the firewall name is not main, then the set value would be instead:
		// $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
		$this->get('session')->set('_security_'.USER_FIREWALL, serialize($token));

		// Fire the login event manually
		$event = new InteractiveLoginEvent($request, $token);
		$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
	}
}
