<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class GuestsInRoomRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
