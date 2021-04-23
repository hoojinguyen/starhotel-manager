<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="service_types")
 * @ORM\Entity(repositoryClass="Romi\Repository\ServiceTypeRepository")
 */
class ServiceType implements \JsonSerializable
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
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 20)           
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 20)
	 */
	private $active;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;




	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'code' => $this->code,
			'active' => $this->active
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
}
