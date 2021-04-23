<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="Romi\Repository\DeviceRepository")
 */
class Device implements \JsonSerializable
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
	 * @ORM\Column(name = "import_date", type="datetime", nullable=false)
	 */
	private $importDate;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

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
			'name' => $this->name,
			'price' => $this->price,
			'code' => $this->code,
			'importDate' => $this->importDate
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
	 * Get the value of importDate
	 */
	public function getImportDate()
	{
		return $this->importDate;
	}

	/**
	 * Set the value of importDate
	 *
	 * @return self
	 */
	public function setImportDate($importDate)
	{
		$this->importDate = $importDate;

		return $this;
	}


		/**
	 * Get the value of code
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Set the value of code
	 *
	 * @return self
	 */
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}


	
}
