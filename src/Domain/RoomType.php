<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="room_type")
 * @ORM\Entity(repositoryClass="Romi\Repository\RoomTypeRepository")
 */
class RoomType implements \JsonSerializable
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
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $name;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $price;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 20)           
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $active;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "people_max", type="integer", nullable=false)
	 */
	private $maxPeople;

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
			'name' => $this->name,
			'price' => $this->price,
			'code' => $this->code,
			'active' => $this->active,
			'maxPeople' => $this->maxPeople
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
	 * Get the value of price
	 *
	 * @return  integer
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * Set the value of price
	 *
	 * @param  integer  $price
	 *
	 * @return  self
	 */
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * Get the value of code
	 *
	 * @return  string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Set the value of code
	 *
	 * @param  string  $code
	 *
	 * @return  self
	 */
	public function setCode(string $code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * Get the value of active
	 *
	 * @return  string
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Set the value of active
	 *
	 * @param  string  $active
	 *
	 * @return  self
	 */
	public function setActive(string $active)
	{
		$this->active = $active;

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
	 * Get the value of maxPeople
	 *
	 * @return  integer
	 */
	public function getMaxPeople()
	{
		return $this->maxPeople;
	}

	/**
	 * Set the value of maxPeople
	 *
	 * @param  integer  $maxPeople
	 *
	 * @return  self
	 */
	public function setMaxPeople($maxPeople)
	{
		$this->maxPeople = $maxPeople;

		return $this;
	}
}
