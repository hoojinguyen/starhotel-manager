<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="Romi\Repository\PaymentRepository")
 */
class Payment implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Booking")
	 * @ORM\JoinColumn(name="booking_id", referencedColumnName="id", nullable=true)
	 */
	private $bookingId;

	/**
	 * @ORM\ManyToOne(targetEntity="BookingRoom")
	 * @ORM\JoinColumn(name="bookingroom_id", referencedColumnName="id", nullable=true)
	 */
	private $bookingRoomId;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $amount;

	/**
	 *
	 * @ORM\Column(name = "payment_at",type="datetime", nullable=false)
	 */
	private $paymentAt;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

		/**
	 * @var string
	 *
	 * @ORM\Column(name = "description",type="string", nullable=false)
	 */
	private $description;

	/**
	 * @var int
	 *
	 * @ORM\Column(name = "created_by",type="integer", nullable=false)
	 */
	private $createdBy;
	/**
	 *
	 * @ORM\Column(name = "created_at",type="datetime", nullable=false)
	 */
	private $createdAt;
	/**
	 * @var int
	 *
	 * @ORM\Column(name = "updated_by",type="integer", nullable=false)
	 */
	private $updatedBy;
	/**
	 *
	 * @ORM\Column(name = "updated_at",type="datetime", nullable=false)
	 */
	private $updatedAt;

	/**
	 * Set the value of createdBy
	 *
	 * @param int $createdBy
	 *
	 * @return self
	 */
	public function setCreatedBy($createdBy)
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
	public function setUpdatedBy($updatedBy)
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
			'bookingId' => $this->bookingId,
			'amount' => $this->amount
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
	 * Get the value of bookingId
	 */
	public function getBookingId()
	{
		return $this->bookingId;
	}

	/**
	 * Set the value of bookingId
	 *
	 * @return  self
	 */
	public function setBookingId($bookingId)
	{
		$this->bookingId = $bookingId;

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
	public function setBookingRoomId($bookingRoomId)
	{
		$this->bookingRoomId = $bookingRoomId;

		return $this;
	}

	/**
	 * Get the value of paymentAt
	 */
	public function getPaymentAt()
	{
		return $this->paymentAt;
	}

	/**
	 * Set the value of paymentAt
	 *
	 * @return  self
	 */
	public function setPaymentAt($paymentAt)
	{
		$this->paymentAt = $paymentAt;

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
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

		/**
	 * Get the value of amount
	 *
	 * @return  int
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
