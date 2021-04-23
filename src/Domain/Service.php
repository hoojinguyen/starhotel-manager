<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="Romi\Repository\ServiceRepository")
 */
class Service implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="ServiceType")
	 * @ORM\JoinColumn(name="servicetype_id", referencedColumnName="id", nullable=false)
	 */
	private $serviceTypeId;

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
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $unit;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $status;

		/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $code;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;


	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'serviceTypeId' => $this->serviceTypeId,
			'name' => $this->name,
			'price' => $this->price,
			'status' => $this->status,
			'unit' => $this->unit,
			'code' => $this->code
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
	 * Get the value of serviceTypeId
	 */ 
	public function getServiceTypeId()
	{
		return $this->serviceTypeId;
	}

	/**
	 * Set the value of serviceTypeId
	 *
	 * @return  self
	 */ 
	public function setServiceTypeId($serviceTypeId)
	{
		$this->serviceTypeId = $serviceTypeId;

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
	 * Get the value of unit
	 *
	 * @return  string
	 */ 
	public function getUnit()
	{
		return $this->unit;
	}

	/**
	 * Set the value of unit
	 *
	 * @param  string  $unit
	 *
	 * @return  self
	 */ 
	public function setUnit(string $unit)
	{
		$this->unit = $unit;

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
}
