<?php

namespace Romi\BusinessLogic;

use Romi\Domain\User;
use Romi\Shared\Cryptography;

class UserLoginLogic extends BaseLogic
{


	public function checkUserLogin($username, $password)
	{
		$error = "";

		$checkUsername = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u')
			->select('u')
			->where('u.username LIKE :username')
			->setParameter('username', '%' . $username . '%')
			->getQuery()
			->getResult();
		if ($checkUsername) {
			$checkPassword = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u')
				->select('u.userType')
				->where('u.password LIKE :password')
				->setParameter('password', '%' . $password . '%')
				->getQuery()
				->getResult();
			if ($checkPassword) {
				return $checkPassword[0][usertype];
			} else {
				$error = "password";
				return $error;
			}
		} else {
			$error = "username";
			return $error;
		}
	}
}
