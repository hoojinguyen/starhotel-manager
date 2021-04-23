<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="Romi\Repository\PriceRepository")
 */
class Price implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="RoomType")
	 * @ORM\JoinColumn(name="roomtype_id", referencedColumnName="id", nullable=false)
	 */
	private $roomTypeId;


	/**
	 * @var string
	 *
	 * @ORM\Column(name = "rent_type", type="string", nullable=false)
	 */
	private $rentType;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "charge_type", type="string", nullable=false)
	 */
	private $chargeType;
	
	/**
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $price;

	/**
	 *
	 * @ORM\Column(name = "start_time",type="datetime", nullable=false)
	 */
	private $startTime;

	/**
	 *
	 * @ORM\Column(name = "end_time",type="datetime", nullable=false)
	 */
	private $endTime;

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
	 * Get the value of rentType
	 *
	 * @return  string
	 */ 
	public function getRentType()
	{
		return $this->rentType;
	}

	/**
	 * Set the value of rentType
	 *
	 * @param  string  $rentType
	 *
	 * @return  self
	 */ 
	public function setRentType(string $rentType)
	{
		$this->rentType = $rentType;

		return $this;
	}

	/**
	 * Get the value of chargeType
	 *
	 * @return  string
	 */ 
	public function getChargeType()
	{
		return $this->chargeType;
	}

	/**
	 * Set the value of chargeType
	 *
	 * @param  string  $chargeType
	 *
	 * @return  self
	 */ 
	public function setChargeType(string $chargeType)
	{
		$this->chargeType = $chargeType;

		return $this;
	}

		/**
	 * Get the value of price
	 */ 
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * Set the value of price
	 *
	 * @return  self
	 */ 
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * Get the value of startTime
	 */ 
	public function getStartTime()
	{
		return $this->startTime;
	}

	/**
	 * Set the value of startTime
	 *
	 * @return  self
	 */ 
	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;

		return $this;
	}

	/**
	 * Get the value of endTime
	 */ 
	public function getEndTime()
	{
		return $this->endTime;
	}

	/**
	 * Set the value of endTime
	 *
	 * @return  self
	 */ 
	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;

		return $this;
	}

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
		];
	}
}
