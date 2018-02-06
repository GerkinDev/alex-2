<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use App\Entity\User;
use App\Service\UriTokenHandler;

class Mailer {
	const MAILBOT_ADDRESS = 'a.ffcc7@gmail.com';

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
		return $this->sendMail(
			'Welcome onboard, '.$user->getFirstName().'!',
			$user->getEmail(),
			'registration',
			[ 'user' => $user,
			'validate_url' => $this->router->generate('validate_account', [
				'token' => rawurlencode($this->tokenHandler->encryptRouteToken($user->getId(), 'validateAccount'))
			], UrlGeneratorInterface::ABSOLUTE_URL)]
		);
	}

	public function sendMailUserResetPassword(User $user){
		return $this->sendMail(
			'Password lost '.$user->getFirstName().'?',
			$user->getEmail(),
			'password_lost',
			[ 'user' => $user,
			'reset_url' => $this->router->generate('reset_password', [
				'token' => rawurlencode($user->getPasswordResetToken()),
			], UrlGeneratorInterface::ABSOLUTE_URL)]
		);
	}

	private function sendMail(string $subject, string $to, string $template, array $args){
		$message = (new \Swift_Message($subject.' - '.$this->getParameter('name')))
		->setFrom(self::MAILBOT_ADDRESS)
		->setTo($to)
		->setBody($this->templating->render( "emails/$template.html.twig", $args ), 'text/html')
		->addPart($this->templating->render( "emails/$template.txt.twig", $args ), 'text/plain');

		return $this->mailer->send($message);
	}
}
