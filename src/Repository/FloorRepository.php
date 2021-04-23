<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class FloorRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}
	public function getCheckedInFloor(){
		$dql = "SELECT f FROM Floor WHERE f.id IN (SELECT o.)";
	}

}
