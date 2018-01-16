<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use App\Entity\User;
use App\Service\UriTokenHandler;

class Mailer {
	const MAILBOT_ADDRESS = 'mailbot@example.com';

	private $mailer;
	private $templating;
	private $tokenHandler;
	private $router;

	public function __construct(RouterInterface $router, \Swift_Mailer $mailer, \Twig_Environment $templating, UriTokenHandler $tokenHandler){
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->tokenHandler = $tokenHandler;
		$this->router = $router;
	}

	public function sendMailNewUser(User $user)
	{
		$args = [
			'user' => $user,
			'validate_url' => $this->router->generate('validate_account', [
				'token' => rawurlencode($this->tokenHandler->encryptRouteToken($user->getId(), 'validateAccount'))
			], UrlGeneratorInterface::ABSOLUTE_URL),
		];
		$message = (new \Swift_Message('Welcome onboard, '.$user->getFirstName().'! - '))
			->setFrom(self::MAILBOT_ADDRESS)
			->setTo($user->getEmail())
			->setBody($this->templating->render('emails/registration.html.twig', $args ), 'text/html')
			->addPart($this->templating->render( 'emails/registration.txt.twig', $args ), 'text/plain');

		$this->mailer->send($message);
	}
}