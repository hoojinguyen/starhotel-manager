<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Account;
use Romi\Domain\Profile;
use Romi\Shared\Cryptography;

class AccountLogic extends BaseLogic {

	public function createAccount($payorId, $params) {
		//Find profile if exist
		$profile = $this->getEntityManager()
				->getRepository(Profile::class)
				->findOneBy(array('payorId' => $payorId));
		if (!$profile) {
			$name = $params['data']['account_name'];
			$email = $params['email'];
			$profile = $this->getLogic('Profile')
					->createProfile($payorId, $name, $email);
		} else {
			if ($profile->getEmail() !== $params['email']) {
				$profile->setEmail($params['email']);
				$this->saveOrUpdate($profile);
			}
		}

		$encryptData = Cryptography::Encrypt(json_encode($params['data']));
		$account = new Account();
		$account->setProfile($profile)
				->setData($encryptData)
				->setType($params['type']);

		$this->saveOrUpdate($account);
		return $account;
	}

	public function loadAccount($accountId) {
		$account = $this->getEntityManager()->find(Account::class, $accountId);
		return $account;
	}
	
	public function updateAccount($accountId, $params) {
		$profile = $this->getEntityManager()
						->getRepository(Profile::class)
						->findOneBy(array('payorId' => $params['payorId']));
		if ($profile){
			$account = $this->getEntityManager()
				->getRepository(Account::class)
				->findOneBy(array('id' => $accountId, 'profile' => $profile));
			
			if ($account){
				$encryptData = Cryptography::Encrypt(json_encode($params['data']));
				$account->setData($encryptData);
				
				$this->saveOrUpdate($account);
				return true;
			}
		}

		return false;
	}
	
	public function deleteAccount($accountId) {
		$account = $this->getEntityManager()
						->getRepository(Account::class)
						->find($accountId);
		
		if ($account){
			$this->getEntityManager()->remove($account);
			$this->getEntityManager()->flush();
			return true;
		}
		return false;
	}

}
