<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class GuestRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
