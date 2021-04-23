<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Romi\Repository\BookingRepository")
 */
class Booking implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Contact")
	 * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=true)
	 */
	private $contactId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "booking_code",type="string",length = 8, nullable=true)
	 */
	private $bookingCode;

	/**
	 * 
	 * @ORM\Column(name = "day_booking",type="datetime", nullable=true)
	 */
	private $dayBooking;

	/**
	 *
	 * @ORM\Column(name = "day_checkin",type="date", nullable=true)
	 */
	private $dayCheckin;

	/**
	 *
	 * @ORM\Column(name = "day_checkout", type="date", nullable=true)
	 */
	private $dayCheckout;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $adult;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $child;


	/**
	 * @var string
	 *
	 * @ORM\Column(name = "note",type="string", nullable=true)
	 */
	private $note;


	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "status",type="integer", nullable=true)
	 */
	private $status;



	/**
	 * @var integer
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
	 * @var integer
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
	 * Get the value of contactId
	 */
	public function getContactId()
	{
		return $this->contactId;
	}

	/**
	 * Set the value of contactId
	 *
	 * @return  self
	 */
	public function setContactId($contactId)
	{
		$this->contactId = $contactId;

		return $this;
	}


	/**
	 * Get the value of bookingCode
	 *
	 * @return  string
	 */
	public function getBookingCode()
	{
		return $this->bookingCode;
	}

	/**
	 * Set the value of bookingCode
	 *
	 * @param  string  $bookingCode
	 *
	 * @return  self
	 */
	public function setBookingCode(string $bookingCode)
	{
		$this->bookingCode = $bookingCode;

		return $this;
	}

	/**
	 * Get the value of dayBooking
	 */
	public function getDayBooking()
	{
		return $this->dayBooking;
	}

	/**
	 * Set the value of dayBooking
	 * 
	 * @return  self
	 */
	public function setDayBooking($dayBooking)
	{
		$this->dayBooking = $dayBooking;

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
	 * Get the value of adult
	 *
	 * @return  integer
	 */
	public function getAdult()
	{
		return $this->adult;
	}

	/**
	 * Set the value of adult
	 *
	 * @param  integer  $adult
	 *
	 * @return  self
	 */
	public function setAdult($adult)
	{
		$this->adult = $adult;

		return $this;
	}

	/**
	 * Get the value of child
	 *
	 * @return  integer
	 */
	public function getChild()
	{
		return $this->child;
	}

	/**
	 * Set the value of child
	 *
	 * @param  integer  $child
	 *
	 * @return  self
	 */
	public function setChild($child)
	{
		$this->child = $child;

		return $this;
	}

	/**
	 * Get the value of note
	 *
	 * @return  string
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * Set the value of note
	 *
	 * @param  string  $note
	 *
	 * @return  self
	 */
	public function setNote(string $note)
	{
		$this->note = $note;

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
	public function setStatus(int $status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get the value of createdBy
	 *
	 * @return  int
	 */
	public function getCreatedBy()
	{
		return $this->createdBy;
	}

	/**
	 * Set the value of createdBy
	 *
	 * @param  int  $createdBy
	 *
	 * @return  self
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
	 * @return  self
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get the value of updatedBy
	 *
	 * @return  int
	 */
	public function getUpdatedBy()
	{
		return $this->updatedBy;
	}

	/**
	 * Set the value of updatedBy
	 *
	 * @param  int  $updatedBy
	 *
	 * @return  self
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
	 * @return  self
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}


	public function jsonSerialize()
	{
		return [
			'bookingCode' => $this->bookingCode,
			'id' => $this->id,
			'contactId' => $this->contactId,
			'numChild' => $this->child,
			'adult' => $this->adult,
			'dayBooking' => $this->dayBooking,
			'dayCheckin' => $this->dayCheckin,
			'dayCheckout' => $this->dayCheckout,
			'status' => $this->status,
			'note' => $this->note,
		];
	}
}
