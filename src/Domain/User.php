<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Romi\Repository\UserRepository")
 */
class User implements \JsonSerializable
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
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $password;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "user_type", type="string", nullable=false)
	 */
	private $userType;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
	 */
	private $tenantId;

	/**
	 * @ORM\ManyToOne(targetEntity="UserRole")
	 * @ORM\JoinColumn(name="user_role_id", referencedColumnName="id", nullable=false)
	 */
	private $userRoleId;

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

	/**
	 * Get the value of userRoleId
	 */
	public function getUserRoleId()
	{
		return $this->userRoleId;
	}

	/**
	 * Set the value of userRoleId
	 *
	 * @return  self
	 */
	public function setUserRoleId($userRoleId)
	{
		$this->userRoleId = $userRoleId;

		return $this;
	}

	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'username' => $this->username,
			'password' => $this->password,
			'usertype' => $this->usertype,
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
	 * Get the value of username
	 *
	 * @return  string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set the value of username
	 *
	 * @param  string  $username
	 *
	 * @return  self
	 */
	public function setUsername(string $username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get the value of password
	 *
	 * @return  string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @param  string  $password
	 *
	 * @return  self
	 */
	public function setPassword(string $password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of userType
	 *
	 * @return  string
	 */
	public function getUserType()
	{
		return $this->userType;
	}

	/**
	 * Set the value of userType
	 *
	 * @param  string  $userType
	 *
	 * @return  self
	 */
	public function setUserType(string $userType)
	{
		$this->userType = $userType;

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
