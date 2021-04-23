<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="booking_status")
 * @ORM\Entity(repositoryClass="Romi\Repository\BookingStatusRepository")
 */
class BookingStatus implements \JsonSerializable
{

	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\Column(type="text")
	 */
	private $name;

	/**
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $code;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenant_id;

	public function getTenantId(){
		return $this->tenant_id;
	}
	
	public function setTenantId($tenant){
		$this->tenant_id = $tenant;
		return $this;
	}

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
		];
	}

	/**
	 * Get the value of name
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 *
	 * @return  self
	 */ 
	public function setName($name)
	{
		$this->name = $name;

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
	 * @return  self
	 */ 
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}
}
