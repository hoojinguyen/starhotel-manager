<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="Romi\Repository\ContactRepository")
 */
class Contact implements \JsonSerializable
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
	 * @ORM\Column(type="string", nullable=true, length = 256)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "phone_number",type="string", nullable=true, length = 20)
	 */
	private $phoneNumber;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "email",type="string", nullable=true)
	 */
	private $email;


	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $company;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $source;

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'phoneNumber' => $this->phoneNumber,
			'email' => $this->email,
		];
	}

	/**
	 * Get the value of id
	 *
	 * @return  int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @param  int  $id
	 *
	 * @return  self
	 */
	public function setId(int $id)
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
	 * Get the value of phoneNumber
	 *
	 * @return  string
	 */
	public function getPhoneNumber()
	{
		return $this->phoneNumber;
	}

	/**
	 * Set the value of phoneNumber
	 *
	 * @param  string  $phoneNumber
	 *
	 * @return  self
	 */
	public function setPhoneNumber(string $phoneNumber)
	{
		$this->phoneNumber = $phoneNumber;

		return $this;
	}

	/**
	 * Get the value of email
	 *
	 * @return  string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @param  string  $email
	 *
	 * @return  self
	 */
	public function setEmail(string $email)
	{
		$this->email = $email;

		return $this;
	}


	/**
	 * Get the value of company
	 *
	 * @return  string
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * Set the value of company
	 *
	 * @param  string  $company
	 *
	 * @return  self
	 */
	public function setCompany(string $company)
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * Get the value of source
	 *
	 * @return  string
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * Set the value of source
	 *
	 * @param  string  $source
	 *
	 * @return  self
	 */
	public function setSource(string $source)
	{
		$this->source = $source;

		return $this;
	}
}
