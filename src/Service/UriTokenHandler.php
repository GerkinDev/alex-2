<?php

namespace App\Service;

use App\Entity\User;

class UriTokenHandler {
	const TOKEN_ENCRYPTION_METHOD = 'aes128';
	const ROUTES_TOKENS_PASSWORDS = [
		'validateAccount' => 'jfdhbpa)àé"r7543',
		'resetPassword' => '-))àè!ycdjhbqezr',
	];
	const ROUTES_TOKENS_PASSWORD_DEFAULT = 'NoRouteDefined';
	const IV_FILLER = ' ';

	private function getRoutePassword($route){
		if(isset(self::ROUTES_TOKENS_PASSWORDS[$route])){
			return self::ROUTES_TOKENS_PASSWORDS[$route];
		}
		return self::ROUTES_TOKENS_PASSWORD_DEFAULT;
	}
	
	private function getRouteIv($routePassword, $encryption){
		$ivLen = openssl_cipher_iv_length($encryption);
		return substr($routePassword.str_repeat(self::IV_FILLER, $ivLen), 0, $ivLen);
	}
	
	public function encryptRouteToken($token, $route, $encryption = UriTokenHandler::TOKEN_ENCRYPTION_METHOD){
		$password = $this->getRoutePassword($route);
		$iv = $this->getRouteIv($password, $encryption);
		return openssl_encrypt (strval($token), $encryption, $password, 0, $iv);
	}
	
	public function decryptRouteToken($data, $route, $encryption = UriTokenHandler::TOKEN_ENCRYPTION_METHOD){
		$password = $this->getRoutePassword($route);
		$iv = $this->getRouteIv($password, $encryption);
		return openssl_decrypt (strval($data), $encryption, $password, 0, $iv);
	}
}
