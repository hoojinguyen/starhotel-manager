<?php

namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

use Romi\Domain\Config;

class ConfigRepository extends EntityRepository
{
	public function getNightPrice($roomType)
	{
		$dql = null;
		switch ($roomType) {
			case 1: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('nightPrice1', 'weekendNightPrice1', 'OTHourPrice1', 'earlyHourPrice1', 'maxPeople1','surcharge1')";
					break;
				}
			case 2: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('nightPrice2', 'weekendNightPrice2', 'OTHourPrice2', 'earlyHourPrice2', 'maxPeople2','surcharge2')";
					break;
				}
			default: {
					break;
				}
		}
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}
	public function getDayPrice($roomType)
	{
		$dql = null;
		switch ($roomType) {
			case 1: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('dayPrice1', 'weekendDayPrice1', 'OTHourPrice1', 'earlyHourPrice1', 'maxPeople1','surcharge1')";
					break;
				}
			case 2: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('dayPrice2', 'weekendDayPrice2', 'OTHourPrice2', 'earlyHourPrice2', 'maxPeople2','surcharge2')";
					break;
				}
			default: {
					break;
				}
		}
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}
	public function getHourPrice($roomType)
	{ 
		$dql = null;
		switch ($roomType) {
			case 1: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('firstHourPrice1', 'nextHourPrice1','firstHourAfter23h1', 'nextHourAfter23h1', 'maxPeople1','dayPrice1','nightPrice1','weekendDayPrice1','weekendNightPrice1','surcharge1')";
					break;
				}
			case 2: {
					$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('firstHourPrice2', 'nextHourPrice2', 'firstHourAfter23h2','nextHourAfter23h2', 'maxPeople2','dayPrice2','nightPrice2','weekendDayPrice2','weekendNightPrice2','surcharge2')";
					break;
				}
			default: {
					break;
				}
		}
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}
	public function getSurcharge()
	{
		$dql = "SELECT config.value FROM Romi\Domain\Config config WHERE config.key = 'surcharge'";
		return $this->getEntityManager()->createQuery($dql)->getResult()[0]['value'];
	}
	public function getMaxPeople($roomType){
		$dql = null;
		switch ($roomType) {
			case 1: {
					$dql = "SELECT config.value FROM Romi\Domain\Config config WHERE config.key = 'maxPeople1'";
					break;
				}
			case 2: {
					$dql = "SELECT config.value FROM Romi\Domain\Config config WHERE config.key = 'maxPeople2'";
					break;
				}
			default: {
					break;
				}
		}
		return $this->getEntityManager()->createQuery($dql)->getResult()[0]['value'];
	}


	public function getPriceSingleRoom(){
		$dql = "SELECT config.id, config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('firstHourPrice1', 'nextHourPrice1', 'firstHourAfter23h1', 'nextHourAfter23h1', 'dayPrice1','nightPrice1','weekendDayPrice1','weekendNightPrice1','OTHourPrice1','earlyHourPrice1','surcharge1')";
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}

	public function getPriceDoubleRoom(){
		$dql = "SELECT config.id, config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('firstHourPrice2', 'nextHourPrice2', 'firstHourAfter23h2', 'nextHourAfter23h2', 'dayPrice2','nightPrice2','weekendDayPrice2','weekendNightPrice2','OTHourPrice2','earlyHourPrice2','surcharge2')";
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}
}
