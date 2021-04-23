<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * The User class demonstrates how to annotate a simple
 * PHP class to act as a Doctrine entity.
 *
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="Romi\Repository\TenantRepository")
 * @ORM\Table(name="tenants")
 */
class Tenant implements \JsonSerializable
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
	 * @ORM\Column(type="string", length=50, nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=50, nullable=false)
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=false)
	 */
	private $setting;

	//Consider whether store to database.
	private $token;


	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
		];
	}

	/**
	 * Get the value of id
	 *
	 * @return  string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @param  string  $id
	 *
	 * @return  self
	 */
	public function setId(string $id)
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
	 * Get the value of setting
	 *
	 * @return  string
	 */
	public function getSetting()
	{
		return $this->setting;
	}

	/**
	 * Set the value of setting
	 *
	 * @param  string  $setting
	 *
	 * @return  self
	 */
	public function setSetting(string $setting)
	{
		$this->setting = $setting;

		return $this;
	}

	/**
	 * Get the value of token
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set the value of token
	 *
	 * @return  self
	 */
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}
}
