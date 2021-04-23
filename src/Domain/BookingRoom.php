<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="booking_room")
 * @ORM\Entity(repositoryClass="Romi\Repository\BookingRoomRepository")
 */
class BookingRoom implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

	/**
	 * @ORM\ManyToOne(targetEntity="Room")
	 * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=false)
	 */
	private $roomId;

	/**
	 * @ORM\ManyToOne(targetEntity="Booking")
	 * @ORM\JoinColumn(name="booking_id", referencedColumnName="id", nullable=false)
	 */
	private $bookingId;


	/**
	 *
	 * @ORM\Column(name = "day_checkin",type="date", nullable=true)
	 */
	private $dayCheckin;

	/**
	 *
	 *
	 * @ORM\Column(name = "day_checkout",type="date", nullable=true)
	 */
	private $dayCheckout;

	/**
	 *
	 *
	 * @ORM\Column(name = "time_arrival",type="time", nullable=true)
	 */
	private $timeArrival;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "num_guest",type="integer", nullable=true)
	 */
	private $numGuest;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "status",type="integer", nullable=true)
	 */
	private $status;

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
	 * Get the value of dayCheckin
	 */
	public function getDayCheckin()
	{
		return $this->dayCheckin;
	}

	/**
	 * Set the value of dayCheckin
	 *
	 * @return  self
	 */
	public function setDayCheckin($dayCheckin)
	{
		$this->dayCheckin = $dayCheckin;

		return $this;
	}

	/**
	 * Get the value of dayCheckout
	 */
	public function getDayCheckout()
	{
		return $this->dayCheckout;
	}

	/**
	 * Set the value of dayCheckout
	 *
	 * @return  self
	 */
	public function setDayCheckout($dayCheckout)
	{
		$this->dayCheckout = $dayCheckout;

		return $this;
	}

	/**
	 * Get the value of timeArrival
	 */
	public function getTimeArrival()
	{
		return $this->timeArrival;
	}

	/**
	 * Set the value of timeArrival
	 *
	 * @return  self
	 */
	public function setTimeArrival($timeArrival)
	{
		$this->timeArrival = $timeArrival;

		return $this;
	}

	/**
	 * Get the value of numGuest
	 *
	 * @return  integer
	 */
	public function getGuestNumber()
	{
		return $this->numGuest;
	}

	/**
	 * Set the value of numGuest
	 *
	 * @param  integer  $numGuest
	 *
	 * @return  self
	 */
	public function setGuestNumber($numGuest)
	{
		$this->numGuest = $numGuest;

		return $this;
	}


	/**
	 * Get the value of status
	 *
	 * @return  integer
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set the value of status
	 *
	 * @param  integer  $status
	 *
	 * @return  self
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}


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
			'roomId' => $this->roomId,
			'bookingId' => $this->bookingId,
			'rentType' => $this->rentType,
			'dayCheckin' => $this->dayCheckin,
			'dayCheckout' => $this->dayCheckout,
			'timeArrival' => $this->timArrival,
			'numGuest' => $this->numGuest,
			'status' => $this->status
		];
	}
}
