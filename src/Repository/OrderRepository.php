<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository {

	public function anyMethodToFindData() {
		return 'data from repository';
	}

	public function getOrderInfo($orderId){
		$dql = "SELECT r.id, IDENTITY(r.roomTypeId) as roomType, o.rentType, o.dateCheckin, o.numPeople FROM Romi\Domain\Room r INNER JOIN Romi\Domain\Order o WHERE r.id = (SELECT IDENTITY(c.roomId) FROM Romi\Domain\Order c WHERE c.id = :orderId) AND o.id = :orderId";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('orderId', $orderId);
		return $query->getResult()[0];
	}
	

}




