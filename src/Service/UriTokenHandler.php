<?php

namespace App\Service;

use App\Entity\User;

class UriTokenHandler {
	const TOKEN_ENCRYPTION_METHOD = 'aes128';
	const ROUTES_TOKENS_PASSWORDS = [
		'validateAccount' => 'jfdhbpa)àé"r7543',
	];
	const ROUTES_TOKENS_PASSWORD_DEFAULT = 'NoRouteDefined';

	private function getRoutePassword($route){
		if(isset(self::ROUTES_TOKENS_PASSWORDS[$route])){
			return self::ROUTES_TOKENS_PASSWORDS[$route];
		}
		return self::ROUTES_TOKENS_PASSWORD_DEFAULT;
	}
	
	private function getRouteIv($routePassword){
		$ivLen = openssl_cipher_iv_length(self::TOKEN_ENCRYPTION_METHOD);
		return substr($routePassword.str_repeat(' ', $ivLen), 0, $ivLen);
	}
	
	public function encryptRouteToken($token, $route){
		$password = $this->getRoutePassword($route);
		$iv = $this->getRouteIv($password);
		return openssl_encrypt (strval($token), self::TOKEN_ENCRYPTION_METHOD, $password, 0, $iv);
	}
	
	public function decryptRouteToken($data, $route){
		$password = $this->getRoutePassword($route);
		$iv = $this->getRouteIv($password);
		return openssl_decrypt (strval($data), self::TOKEN_ENCRYPTION_METHOD, $password, 0, $iv);
	}
}
