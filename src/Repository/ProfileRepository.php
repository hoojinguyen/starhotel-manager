<?php

namespace Romi\Repository;

use Romi\Domain\Profile;
use Doctrine\ORM\EntityRepository;

class ProfileRepository extends EntityRepository {

	public function anyMethodToFindData(Profile $profile) {
		return 'data from repository';
	}

}
