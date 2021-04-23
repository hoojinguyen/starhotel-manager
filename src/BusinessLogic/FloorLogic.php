<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Floor;
use Romi\Shared\Cryptography;
use Romi\Domain\Tenant;

class FloorLogic extends BaseLogic {

	
    public function loadFloor()
	{
		
		return $this->getEntityManager()->getRepository(Floor::class)->createQueryBuilder('f')
		->select('f.id, f.name, f.code, f.active')
		->getQuery()
		->getResult();
		
	}

	public function loadCheckedInFloor()
	{
		$dql = "SELECT floor FROM Romi\Domain\Floor floor WHERE floor.id IN (SELECT IDENTITY(room.floorId) FROM Romi\Domain\Room room WHERE room.id IN (SELECT IDENTITY(o.roomId) FROM Romi\Domain\Order o WHERE o.status = 'Đã được đặt'))";
		$query = $this->getEntityManager()->createQuery($dql);
		return $query->getResult();
	}



	// save and update
	public function createFloor($params) {
			
		$floor = $this->createFloorEntity($params);
		$this->saveOrUpdate($floor);
		return true;
	}

	protected function createFloorEntity($params){
		$floor = new Floor();		
		$this->setFloorEntity($params, $floor);
		return $floor;
	}

	public function setFloorEntity($params, Floor &$floor){

		$createdAt = new \DateTime($params['createdAt']);
		$updatedAt = new \DateTime($params['updatedAt']);
		
		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $params['idTenant']));

		$floor->setTenantId($tenantId);
		$floor->setName($params['name']);
		$floor->setCode($params['code']);
		$floor->setActive($params['active']);

		$floor->setCreatedAt($createdAt); 
		$floor->setUpdatedAt($updatedAt);
		
		$floor->setCreatedBy($params['createdBy']); 
		$floor->setUpdatedBy($params['updatedBy']); 
	}
	

	
	
	



	// delete floor
	public function deleteFloor($params){
		
		$floor = $this->getEntityManager()
		->getRepository(Floor::class)
		//->findOneBy((array('id' => $params['id'])))
		->findOneBy(array('id' => $params['id']));
		if ($floor){
			$floor->setActive($params['active']);
			$this->getEntityManager()->flush();
			return true;
		}
		else {
			return false;
		}
	}


	// get info by id
	public function loadFloorById($idFloor) {
		$floor = $this->getEntityManager()
		->getRepository(Floor::class)
		->findOneBy(array('id' => $idFloor));
		return $floor;
	}

	
	// update
	public function updateFloor($params){
		$floor = $this->getEntityManager()->getRepository(Floor::class)->findOneBy(array('id' => $params['id']));
		if (!$floor){
			return false;
		}
		$this->setFloorEntity($params, $floor);
		$this->getEntityManager()->flush();
		return true;
	}

	





	


}
