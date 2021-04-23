<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
