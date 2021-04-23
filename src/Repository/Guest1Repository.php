<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class Guest1Repository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

}
