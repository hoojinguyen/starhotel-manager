<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class ServiceTypeRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
