<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Device;
use Romi\Domain\RoomType;

use Romi\Domain\DeviceInRoom;
use Romi\Domain\ServiceType;
use Romi\Shared\Cryptography;
use Romi\Domain\Room;
use Romi\Domain\Tenant;

class DeviceManageLogic extends BaseLogic
{

	
	public function loadRoom()
	{
		return $this->getEntityManager()
		->getRepository(Room::class)->createQueryBuilder('r')
		->select('r.id , r.name')
		->getQuery()
		->getResult();
		
	}

	public function loadAllDevice()
	{
		$devices = $this->getEntityManager()->getRepository(Device::class)->findAll();
		$data = array();
		foreach ($devices as $device) {
			array_push($data, [
				'code' => $device->getCode(),
				'name' => $device->getName(),
				'price' => $this->getLogic('PriceConfig')->formatMoneyVND($device->getPrice()),
				'importDate' => date_format($device->getImportDate(), 'd-m-Y'),
				'id' => $device->getId()
			]);
		}
		return $data;
	}

	public function loadDeviceInRoom($roomId)
	{
		$dql = "SELECT deviceInRoom.id, deviceInRoom.quantity, device.name , device.code FROM Romi\Domain\Device device, Romi\Domain\DeviceInRoom deviceInRoom WHERE deviceInRoom.roomId = :roomId AND IDENTITY(deviceInRoom.deviceId) = device.id AND deviceInRoom.quantity > 0";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('roomId', $roomId);

		return $query->getResult();
	}

	// save and update
	public function saveDevice($data)
	{
		$service = $this->createDeviceEntity($data);
		$this->saveOrUpdate($service);
		return true;
	}

	public function saveDeviceInRoom($roomId, $deviceId, $quantity,$tenantId){

		// $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $tenantId));
		$room = $this->getEntityManager()->getRepository(Room::class)->findOneBy(['id' => $roomId]);
		$device =  $this->getEntityManager()->getRepository(Device::class)->findOneBy(['id' => $deviceId]);
		$deviceInRoom = $this->getEntityManager()->getRepository(DeviceInRoom::class)->findOneBy(['roomId' => $room, 'deviceId' => $device]);
		if ($deviceInRoom){
			$deviceInRoom->setQuantity($deviceInRoom->getQuantity() + $quantity);
		} else {
			$deviceInRoom = new DeviceInRoom();
			// $deviceInRoom->setTenantId($tenantId);
			$deviceInRoom->setDeviceId($device);
			$deviceInRoom->setRoomId($roomId);
			$deviceInRoom->setQuantity($quantity);
			
		}
		$this->saveOrUpdate($deviceInRoom);
		return true;
	}
	
	public function updateDevice($id, $data)
	{
		$service = $this->getEntityManager()->getRepository(Device::class)->findOneBy(array('id' => $id));
		if (!$service) {
			return false;
		}
		$this->setDeviceEntity($data, $service);
		$this->getEntityManager()->flush();
		return true;
	}

	public function updateDeviceInRoom($id, $quantity){
		$deviceInRoom = $this->getEntityManager()->getRepository(DeviceInRoom::class)->findOneBy(['id' => $id]);
		if(!$deviceInRoom) {
			return false;
		} else {
			$deviceInRoom->setQuantity($quantity);
			$this->saveOrUpdate($deviceInRoom);
			return $deviceInRoom->getQuantity();
		}
	}
	// delete

	public function deleteDevice($id)
	{
		$device = $this->getEntityManager()
			->getRepository(Device::class)
			->findOneBy(array('id' => $id));
		if ($device) {
			$this->delete($device);
			return true;
		} else {
			return false;
		}
	}

	public function deleteDeviceInRoom($id)
	{
		$deviceInRoom = $this->getEntityManager()
			->getRepository(DeviceInRoom::class)
			->findOneBy(array('id' => $id));
		if ($deviceInRoom) {
			$roomType = $deviceInRoom->getRoomTypeId()->getId();
			$this->delete($deviceInRoom);
			$this->getEntityManager()->flush();
			return $roomType;
		} else {
			return false;
		}
	}


	// Support Function

	protected function createDeviceEntity($data)
	{
		$device = new Device();
		$this->setDeviceEntity($data, $device);
		return $device;
	}

	public function setDeviceEntity($data, Device &$device)
	{

		$importDate = new \Datetime($data['importDate']);
		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $data['idTenant']));

		$device->setTenantId($tenantId);
		$device->setName($data['name']);
		$device->setPrice($data['price']);
		$device->setCode($data['code']);
		$device->setImportDate($importDate);

		$device->setCreatedBy($data['createdBy']);
		$device->setUpdatedBy($data['updatedBy']);
		$device->setCreatedAt($importDate);
		$device->setUpdatedAt($importDate);

	}

}