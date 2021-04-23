<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class EmployeeRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
