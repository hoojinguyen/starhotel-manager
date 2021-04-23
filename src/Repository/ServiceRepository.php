<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class ServiceRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
