<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class PaymentRefRepository extends EntityRepository {

    public function anyMethodToFindData() {
		return 'data from repository';
	}

}
