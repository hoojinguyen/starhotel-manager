<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class DeviceRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
