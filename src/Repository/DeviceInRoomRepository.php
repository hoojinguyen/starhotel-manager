<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class DeviceInRoomRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
