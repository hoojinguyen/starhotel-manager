<?php

namespace Romi\Tests\Functional;

use Romi\Tests\BaseTestCase;
use Romi\Shared\Cryptography;

class AccountTest extends BaseTestCase {

	/** @test */
	public function create_new_profile_with_account() {
	
		$payorId = 1;
		$params = [
			'payorId' => $payorId,
			'email' => 'test@domain.com',
			'data' => [
				"pan" => "4111-1111-1111-1111",
				"account_name" => "Elton John",
				"expired_date" => "08/2022",
				"cvv" => "292"
			],
			'type' => 'Debit'
		];
		
		$accountLogic = $this->getLogic('Account');
		$newAccount = $accountLogic->createAccount($payorId, $params);
		
		$this->assertEquals($newAccount->getProfile()->getEmail(), $params['email']);
		$this->assertNotNull($newAccount->getId());
		$getDataback = json_decode(Cryptography::Decrypt($newAccount->getData()));
		$this->assertEquals($getDataback->pan, $params['data']['pan']);
	}

}
