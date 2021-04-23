<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="Romi\Repository\BillRepository")
 */
class Bill implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Discount")
	 * @ORM\JoinColumn(name="discount_id", referencedColumnName="id", nullable=false)
	 */
	private $discountId;

		/**
	 * @var int
	 *
	 * @ORM\Column(name = "price_discount", type="integer", nullable=false)
	 */
	private $priceDiscount;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $amount;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $deposited;

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
		];
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
	 * Get the value of discountId
	 */ 
	public function getDiscountId()
	{
		return $this->discountId;
	}

	/**
	 * Set the value of discountId
	 *
	 * @return  self
	 */ 
	public function setDiscountId($discountId)
	{
		$this->discountId = $discountId;

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

	/**
	 * Get the value of deposited
	 *
	 * @return  int
	 */ 
	public function getDeposited()
	{
		return $this->deposited;
	}

	/**
	 * Set the value of deposited
	 *
	 * @param  integer  $deposited
	 *
	 * @return  self
	 */ 
	public function setDeposited($deposited)
	{
		$this->deposited = $deposited;

		return $this;
	}


	/**
	 * Get the value of priceDiscount
	 *
	 * @return  int
	 */ 
	public function getPriceDiscount()
	{
		return $this->priceDiscount;
	}

	/**
	 * Set the value of priceDiscount
	 *
	 * @param  integer  $priceDiscount
	 *
	 * @return  self
	 */ 
	public function setPriceDiscount($priceDiscount)
	{
		$this->priceDiscount = $priceDiscount;

		return $this;
	}

}
