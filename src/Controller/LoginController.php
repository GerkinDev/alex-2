<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

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
	public function signupCheck(Request $request)
	{
		$query = $request->request;
		var_dump($query);

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
		->setPassword($query->get('password'))
		->setEmail($query->get('email'));

		// tell Doctrine you want to (eventually) save the Product (no queries yet)
		$em->persist($user);

		// actually executes the queries (i.e. the INSERT query)
		$em->flush();

		// replace this line with your own code!
		return $this->render('@Maker/demoPage.html.twig', [ 'path' => str_replace($this->getParameter('kernel.project_dir').'/', '', __FILE__) ]);
	}
}
