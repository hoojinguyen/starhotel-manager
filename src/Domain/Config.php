<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="Romi\Repository\ConfigRepository")
 */
class Config implements \JsonSerializable
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
	private $key;

	/**
	 *
	 * @ORM\Column(type="text", nullable=false)
	 */
	private $value;

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


	
	public function setValue($value){
		$this->value = $value;
		return $this;
	}


	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'key' => $this->key,
			'value' => $this->value,
		];
	}
}
