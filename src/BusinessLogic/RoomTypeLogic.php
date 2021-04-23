<?php

namespace Romi\BusinessLogic;

use Romi\Domain\RoomType;
use Romi\Domain\Tenant;
use Romi\Shared\Cryptography;

class RoomTypeLogic extends BaseLogic
{
	public function loadRoomType()
	{
	
		return $this->getEntityManager()->getRepository(RoomType::class)->createQueryBuilder('rt')
		->select('rt.id, rt.name, rt.code, rt.price,rt.maxPeople, rt.active')
		->getQuery()
		->getResult();
	}

	// save and update
	public function createRoomType($params)
	{

		$roomType = $this->createRoomTypeEntity($params);
		$this->saveOrUpdate($roomType);
		return true;
	}

	protected function createRoomTypeEntity($params)
	{
		$RoomType = new RoomType();
		$this->setRoomTypeEntity($params, $RoomType);
		return $RoomType;
	}

	public function setRoomTypeEntity($params, RoomType &$roomType)
	{

		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => 1));

		$roomType->setTenantId($tenantId);
		$roomType->setName($params['name']);
		$roomType->setPrice($params['price']);
		$roomType->setCode($params['code']);
		$roomType->setActive($params['active']);
		$roomType->setMaxPeople($params['numOfPeopleStay']);
	}
	
	// delete type room
	public function deleteRoomType($params)
	{
		$roomType = $this->getEntityManager()
			->getRepository(RoomType::class)
			->findOneBy(array('id' => $params['id']));
		if ($roomType) {
			$roomType->setActive($params['active']);
			$this->getEntityManager()->flush();
			return true;
		} 
		
		return false;
	}

	// get info by id
	public function loadRoomTypeById($idRoomType)
	{
		$roomType = $this->getEntityManager()
			->getRepository(RoomType::class)
			->findOneBy(array('id' => $idRoomType));
		return $roomType;
	}

	public function updateRoomType($params)
	{
		$this->logger->info($params['id']);

		$roomType = $this->getEntityManager()->getRepository(RoomType::class)->findOneBy(array('id' => $params['id']));
		if (!$roomType) {

			return false;

		}
		$this->setRoomTypeEntity($params, $roomType);

		$this->getEntityManager()->flush();

		return true;
	}
}
