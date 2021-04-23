<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bill_detail")
 * @ORM\Entity(repositoryClass="Romi\Repository\BillDetailRepository")
 */
class BillDetail implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="BookingRoom")
	 * @ORM\JoinColumn(name="bookingroom_id", referencedColumnName="id", nullable=false)
	 */
	private $bookingRoomId;

		/**
	 * @ORM\ManyToOne(targetEntity="Bill")
	 * @ORM\JoinColumn(name="bill_id", referencedColumnName="id", nullable=false)
	 */
	private $billId;



	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

	/**
	 * 	 * @var string
	 *
	 * @ORM\Column(name = "fee_name",type="string", nullable=false)
	 */
	private $feeName;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $description;

		/**
	 * @var integer
	 *
	 * @ORM\Column(name = "fee_amount", type="integer", nullable=false)
	 */
	private $feeAmount;

		/**
	 * @var integer
	 *
	 * @ORM\Column(name = "type_fee", type="integer", nullable=false)
	 */
	private $typeFee;




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
			'bookingRoomId' => $this->bookingRoomId,
			'billId' =>$this->billId,
			'feeName' => $this->feeName,
			'feeAmount' => $this->feeAmount,
			'typeFee' => $this->typeFee
		];
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
	 * Get the value of billId
	 */
	public function getBillId()
	{
		return $this->billId;
	}

	/**
	 * Set the value of billId
	 *
	 * @return  self
	 */
	public function setBillId($billId)
	{
		$this->billId = $billId;

		return $this;
	}


	// 	/**
	//  * Get the value of serviceDetailId
	//  */
	// public function getServiceDetailId()
	// {
	// 	return $this->serviceDetailId;
	// }

	// /**
	//  * Set the value of serviceDetailId
	//  *
	//  * @return  self
	//  */
	// public function setServiceDetailId($serviceDetailId)
	// {
	// 	$this->serviceDetailId = $serviceDetailId;

	// 	return $this;
	// }

	/**
	 * Get the value of feeName
	 */
	public function getFeeName()
	{
		return $this->feeName;
	}

	/**
	 * Set the value of feeName
	 *
	 * @return  self
	 */
	public function setFeeName($feeName)
	{
		$this->feeName = $feeName;

		return $this;
	}

	/**
	 * Get the value of feeAmount
	 */
	public function getFeeAmount()
	{
		return $this->feeAmount;
	}

	/**
	 * Set the value of feeAmount
	 *
	 * @return  self
	 */
	public function setFeeAmount($feeAmount)
	{
		$this->feeAmount = $feeAmount;

		return $this;
	}

		/**
	 * Get the value of typeFee
	 */
	public function getTypeFee()
	{
		return $this->typeFee;
	}

	/**
	 * Set the value of typeFee
	 *
	 * @return  self
	 */
	public function setTypeFee($typeFee)
	{
		$this->typeFee = $typeFee;

		return $this;
	}

		/**
	 * Get the value of description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the value of description
	 *
	 * @return  self
	 */
	public function setDescription($description)
	{
		$this->description = $description;

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
