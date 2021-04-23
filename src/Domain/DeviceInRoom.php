<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="room_device")
 * @ORM\Entity(repositoryClass="Romi\Repository\DeviceInRoomRepository")
 */
class DeviceInRoom implements \JsonSerializable
{
	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Room")
	 * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=true)
	 */
	private $roomId;

	/**
	 * @ORM\ManyToOne(targetEntity="Device")
	 * @ORM\JoinColumn(name="device_id", referencedColumnName="id", nullable=true)
	 */
	private $deviceId;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $quantity;

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'roomId' => $this->roomId,
			'deviceId' => $this->deviceId,
			'quantity' => $this->quantity
		];
	}

	/**
	 * Get the value of id
	 *
	 * @return  integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @param  integer  $id
	 *
	 * @return  self
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of roomId
	 */
	public function getRoomId()
	{
		return $this->roomId;
	}

	/**
	 * Set the value of roomId
	 *
	 * @return  self
	 */
	public function setRoomId($roomId)
	{
		$this->roomId = $roomId;

		return $this;
	}

	/**
	 * Get the value of deviceId
	 */
	public function getDeviceId()
	{
		return $this->deviceId;
	}

	/**
	 * Set the value of deviceId
	 *
	 * @return  self
	 */
	public function setDeviceId($deviceId)
	{
		$this->deviceId = $deviceId;

		return $this;
	}

	/**
	 * Get the value of quantity
	 *
	 * @return  int
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * Set the value of quantity
	 *
	 * @param  int  $quantity
	 *
	 * @return  self
	 */
	public function setQuantity(int $quantity)
	{
		$this->quantity = $quantity;

		return $this;
	}
}
