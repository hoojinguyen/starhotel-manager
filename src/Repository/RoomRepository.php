<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class RoomRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}
	public function getRoomTypeById($roomId){
		$dql = "SELECT IDENTITY(r.roomTypeId) FROM Romi\Domain\Room r WHERE r.id = :roomId";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('roomId', $roomId);
		return $query->getResult();
	}

}
