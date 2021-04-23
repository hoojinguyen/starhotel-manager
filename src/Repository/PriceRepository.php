<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;
use Romi\Domain\Price;

class PriceRepository extends EntityRepository
{
	public function getDayPrice($roomType)
	{
		$dql = "SELECT p.chargeType, p.price FROM Romi\Domain\Price p WHERE p.roomTypeId = :roomTypeId AND p.rentType = 'DAY'";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('roomTypeId', $roomType);
		return $query->getResult();
	}

	public function getPriceRoomType()
	{
		$dql = "SELECT p.chargeType, p.price, (p.roomTypeId) as roomTypeId FROM Romi\Domain\Price p WHERE p.chargeType = 'DAY_DEFAULT' OR p.chargeType = 'DAY_WEEKEND' ORDER BY p.roomTypeId";
		$query = $this->getEntityManager()->createQuery($dql);
		return $query->getResult();
	}
	
}
