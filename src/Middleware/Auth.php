<?php

namespace Romi\Middleware;

use Doctrine\ORM\EntityManager;
use Romi\Domain\User;
use Firebase\JWT\JWT;
use Slim\Collection;
use Slim\Http\Request;
use Carbon\Carbon;
use Romi\Domain\UserRole;
use Romi\Domain\Resource;
use Romi\Domain\UserRolePrivileges;
use Romi\Domain\Privileges;

class Auth
{

	const IDENTIFIER = 'username';

	private $em;
	private $appConfig;

	public function __construct(EntityManager $em, Collection $appConfig)
	{
		$this->em = $em;
		$this->appConfig = $appConfig;
	}

	public function generateToken(Tenant $tenant)
	{
		$now = Carbon::now();
		$future = Carbon::now()->addHours(2); //expires in 2 hours
		$payload = [
			'iat' => $now->getTimestamp(),
			'exp' => $future->getTimestamp(),
			'jti' => base64_encode(random_bytes(16)),
			'iss' => $this->appConfig['app']['url'], // Issuer
			'tenant' => $tenant->getId(),
			self::IDENTIFIER => $tenant->getUsername()
		];
		$secret = $this->appConfig['jwt']['secret'];
		$token = JWT::encode($payload, $secret, 'HS256');
		return $token;
	}

	public function attempt($username, $password)
	{
		if (!$user = $this->em->getRepository(User::class)->findOneBy([self::IDENTIFIER => $username])) {
			return false;
		}
		$user = $this->em->getRepository(User::class)->findOneBy(['password' => $password]);
		if ($user) {
		
			$_SESSION['user'] = strtoupper($username);
			return true;
		}
		return false;
	}

	public function requestTenant(Request $request)
	{
		if ($token = $request->getAttribute('token')) {
			$tenant = $this->em->getRepository(Tenant::class)->findOneBy(['id' => $token->tenant]);
			return $tenant;
		}
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}
}
