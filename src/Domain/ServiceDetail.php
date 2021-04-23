<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="service_detail")
 * @ORM\Entity(repositoryClass="Romi\Repository\ServiceDetailRepository")
 */
class ServiceDetail implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Service")
	 * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
	 */
	private $serviceId;

	/**
	 * @ORM\ManyToOne(targetEntity="BookingRoom")
	 * @ORM\JoinColumn(name="bookingroom_id", referencedColumnName="id", nullable=false)
	 */
	private $bookingRoomId;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "quantity",type="integer", nullable=false)
	 */
	private $quantity;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $amount;

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
			'quantity' => $this->quantity,
			'amount' => $this->amount,
			'serviceId' => $this->serviceId

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
	 * Get the value of serviceId
	 */
	public function getServiceId()
	{
		return $this->serviceId;
	}

	/**
	 * Set the value of serviceId
	 *
	 * @return  self
	 */
	public function setServiceId($serviceId)
	{
		$this->serviceId = $serviceId;

		return $this;
	}

	/**
	 * Get the value of bookingRoomId
	 */
	public function getBookingRoomId()
	{
		return $this->bookingRoomId;
	}

	/**
	 * Set the value of bookingRoomId
	 *
	 * @return  self
	 */
	public function setBookingROomId($bookingRoomId)
	{
		$this->bookingRoomId = $bookingRoomId;

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

	/**
	 * Get the value of quantity
	 *
	 * @return  integer
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * Set the value of quantity
	 *
	 * @param  integer  $quantity
	 *
	 * @return  self
	 */
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;

		return $this;
	}

	/**
	 * Get the value of amount
	 *
	 * @return  integer
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Set the value of amount
	 *
	 * @param  integer  $amount
	 *
	 * @return  self
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}
}
