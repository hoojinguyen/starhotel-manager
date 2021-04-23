<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="Romi\Repository\DiscountRepository")
 */
class Discount implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="Tenant")
     * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
     */
    private $tenantId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length = 256)
     */
    private $code;


    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length = 256)
     */
    private $type;

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
	 * Get the value of value
	 *
	 * @return  integer
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Set the value of value
	 *
	 * @param  integer  $value
	 *
	 * @return  self
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}
    
    	/**
	 * Get the value of type
	 *
	 * @return  string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set the value of type
	 *
	 * @param  string  $type
	 *
	 * @return  self
	 */
	public function setType(string $type)
	{
		$this->type = $type;

		return $this;
	}

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'code' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
        ];
    }
}
