<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="room")
 * @ORM\Entity(repositoryClass="Romi\Repository\RoomRepository")
 */
class Room implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Floor")
	 * @ORM\JoinColumn(name="floor_id", referencedColumnName="id", nullable=true)
	 */
	private $floorId;

	/**
	 * @ORM\ManyToOne(targetEntity="RoomType")
	 * @ORM\JoinColumn(name="roomtype_id", referencedColumnName="id", nullable=true)
	 */
	private $roomTypeId;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $status;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

	/**
	 * @var int
	 *
	 * @ORM\Column(name = "created_by",type="integer", nullable=true)
	 */
	private $createdBy;
	/**
	 *
	 * @ORM\Column(name = "created_at",type="datetime", nullable=true)
	 */
	private $createdAt;
	/**
	 * @var int
	 *
	 * @ORM\Column(name = "updated_by",type="integer", nullable=true)
	 */
	private $updatedBy;
	/**
	 *
	 * @ORM\Column(name = "updated_at",type="datetime", nullable=true)
	 */
	private $updatedAt;

	/**
	 * Set the value of createdBy
	 *
	 * @param int $createdBy
	 *
	 * @return self
	 */
	public function setCreatedBy(int $createdBy)
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * Get the value of createdAt
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set the value of createdAt
	 *
	 * @return self
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get the value of updatedBy
	 *
	 * @return int
	 */
	public function getUpdatedBy()
	{
		return $this->updatedBy;
	}

	/**
	 * Set the value of updatedBy
	 *
	 * @param int $updatedBy
	 *
	 * @return self
	 */
	public function setUpdatedBy(int $updatedBy)
	{
		$this->updatedBy = $updatedBy;

		return $this;
	}

	/**
	 * Get the value of updatedAt
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * Set the value of updatedAt
	 *
	 * @return self
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'floorId' => $this->floorId,
			'roomTypeId' => $this->roomTypeId,
			'nameRoom' => $this->name,
			'status' => $this->status,
			'description' => $this->description
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
	 * Get the value of floorId
	 */
	public function getFloorId()
	{
		return $this->floorId;
	}

	/**
	 * Set the value of floorId
	 *
	 * @return  self
	 */
	public function setFloorId($floorId)
	{
		$this->floorId = $floorId;

		return $this;
	}

	/**
	 * Get the value of roomTypeId
	 */
	public function getRoomTypeId()
	{
		return $this->roomTypeId;
	}

	/**
	 * Set the value of roomTypeId
	 *
	 * @return  self
	 */
	public function setRoomTypeId($roomTypeId)
	{
		$this->roomTypeId = $roomTypeId;

		return $this;
	}

	/**
	 * Get the value of name
	 *
	 * @return  string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 *
	 * @param  string  $name
	 *
	 * @return  self
	 */
	public function setName(string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the value of description
	 *
	 * @return  string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the value of description
	 *
	 * @param  string  $description
	 *
	 * @return  self
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get the value of status
	 *
	 * @return  string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set the value of status
	 *
	 * @param  string  $status
	 *
	 * @return  self
	 */
	public function setStatus(string $status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get the value of tenantId
	 */
	public function getTenantId()
	{
		return $this->tenantId;
	}

	/**
	 * Set the value of tenantId
	 *
	 * @return  self
	 */
	public function setTenantId($tenantId)
	{
		$this->tenantId = $tenantId;

		return $this;
	}
}
