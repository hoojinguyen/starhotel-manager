<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Profile;

class ProfileLogic extends BaseLogic {

	public function createProfile($payorId, $name, $email): Profile {
		$profile = new Profile();
		$profile->setPayorId($payorId)
				->setName($name)
				->setEmail($email);
		$this->saveOrUpdate($profile);
		return $profile;
	}

}
