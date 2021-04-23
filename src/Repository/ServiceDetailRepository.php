<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class ServiceDetailRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
