<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class TenantRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
