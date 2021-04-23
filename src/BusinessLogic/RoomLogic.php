<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Room;
use Romi\Domain\RoomType;
use Romi\Domain\Floor;
use Romi\Domain\Tenant;
use Romi\Shared\Cryptography;

class RoomLogic extends BaseLogic {

	
    public function loadRoom()
	{
        $rooms = $this->getEntityManager()->getRepository(Room::class)->createQueryBuilder('r')
		->select('r.id,r.name as nameRoom, f.id as floorId , rt.id as roomTypeId, rt.name as roomType,f.name as floor')
		->leftJoin(Floor::class,'f','WITH','f.id=r.floorId')
		->leftJoin(RoomType::class,'rt','WITH','rt.id=r.roomTypeId')
		->orderBy('r.name', 'ASC')
        ->getQuery()
        ->getResult()
        ;
        return $rooms;
	}

	public function getCheckedInRoom(){
		$dql = "SELECT room FROM Romi\Domain\Room room WHERE room.id IN (SELECT IDENTITY(o.roomId) FROM Romi\Domain\Order o WHERE o.status = 'Đã được đặt')";
		$query = $this->getEntityManager()->createQuery($dql);
		return $query->getResult();
	}




	// save and update
	public function createRoom($params) {	
		$room = $this->createRoomEntity($params);
		$this->saveOrUpdate($room);
		return true;
	}

	protected function createRoomEntity($params){
		$room = new Room();		
		$this->setRoomEntity($params, $room);
		return $room;
	}

	public function setRoomEntity($params, Room &$room){

		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => 1));
		
		$floorId = $this->getEntityManager()->getRepository(Floor::class)->findOneBy(array('id' => $params['floorId']));
		$roomTypeId = $this->getEntityManager()->getRepository(RoomType::class)->findOneBy(array('id' => $params['roomTypeId']));

		$room->setTenantId($tenantId);
		$room->setFloorId($floorId);
		$room->setRoomTypeId($roomTypeId);
		$room->setName($params['nameRoom']);
		$room->setDescription($params['description']);
		$room->setStatus($params['status']);
	}
	

	// delete room
	public function deleteRoom($id){
		$room = $this->getEntityManager()
		->getRepository(Room::class)
		->findOneBy(array('id' => $id));
		if ($room){
			$this->delete($room);
			return true;
		}
		else 
			return false;
	}


	// get info by id
	public function loadRoomById($id) {
		// $room = $this->getEntityManager()
		// ->getRepository(Room::class)
		// ->findOneBy(array('id' => $id));
		// return $room;

		return $this->getEntityManager()->getRepository(Room::class)->createQueryBuilder('r')
		->select('r.id, r.name, IDENTITY (r.roomTypeId)	as roomTypeId , IDENTITY (r.floorId) as floorId, r.status')
		->where('r.id = :id')
		->setParameter('id', $id)
		->getQuery()
		->getResult();
	}

	public function updateRoom($params){
		$room = $this->getEntityManager()->getRepository(Room::class)->findOneBy(array('id' => $params['id']));
		if (!$room){
			return false;
		}
		$this->setRoomEntity($params, $room);
		$this->getEntityManager()->flush();
		return true;
	}


	public function fixStatusRoom($params){
		$room = $this->getEntityManager()->getRepository(Room::class)->findOneBy(array('id' => $params['id']));
		if ($room){
			$room->setStatus($params['status']);
			$this->getEntityManager()->flush();
			return true;
		}
			return false;
	}

}
