<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="Romi\Repository\EmployeeRepository")
 */
class Employee implements \JsonSerializable
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
	 * @ORM\Column(type="string", nullable=false, length = 256)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 10)
	 */
	private $gender;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "phone_number",type="string", nullable=false, length = 20)
	 */
	private $phoneNumber;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "id_card", type="string", nullable=false, length = 30)
	 */
	private $idCardNo;

	/**
	 *
	 *
	 * @ORM\Column(name = "year_birth", type="integer", nullable=false)
	 */
	private $yearOfBirth;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 256)
	 */
	private $address;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 120)
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $shift;


	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=false, length = 256)
	 */
	private $position;

	/**
	 *
	 * @ORM\Column(name = "day_work",type="date", nullable=false)
	 */
	private $dayToWork;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=true)
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
			'code' => $this->code,
			'gender' => $this->gender,
			'phoneNumber' => $this->phoneNumber,
			'idCardNo' => $this->idCardNo,
			'address' => $this->address,
			'yearOfBirth' => $this->yearOfBirth,
			'dayToWork' => $this->dayToWork,
			'position' => $this->position,
			'shift' => $this->shift,
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
	 * Get the value of gender
	 *
	 * @return  string
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * Set the value of gender
	 *
	 * @param  string  $gender
	 *
	 * @return  self
	 */
	public function setGender(string $gender)
	{
		$this->gender = $gender;

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
	 * Get the value of idCardNo
	 *
	 * @return  string
	 */
	public function getIdCardNo()
	{
		return $this->idCardNo;
	}

	/**
	 * Set the value of idCardNo
	 *
	 * @param  string  $idCardNo
	 *
	 * @return  self
	 */
	public function setIdCardNo(string $idCardNo)
	{
		$this->idCardNo = $idCardNo;

		return $this;
	}

	/**
	 * Get the value of yearOfBirth
	 */
	public function getYearOfBirth()
	{
		return $this->yearOfBirth;
	}

	/**
	 * Set the value of yearOfBirth
	 *
	 * @return  self
	 */
	public function setYearOfBirth($yearOfBirth)
	{
		$this->yearOfBirth = $yearOfBirth;

		return $this;
	}

	/**
	 * Get the value of address
	 *
	 * @return  string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Set the value of address
	 *
	 * @param  string  $address
	 *
	 * @return  self
	 */
	public function setAddress(string $address)
	{
		$this->address = $address;

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
	 * Get the value of position
	 *
	 * @return  string
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set the value of position
	 *
	 * @param  string  $position
	 *
	 * @return  self
	 */
	public function setPosition(string $position)
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * Get the value of dayToWork
	 */
	public function getDayToWork()
	{
		return $this->dayToWork;
	}

	/**
	 * Set the value of dayToWork
	 *
	 * @return self
	 */
	public function setDayToWork($dayToWork)
	{
		$this->dayToWork = $dayToWork;

		return $this;
	}

	/**
	 * Get the value of shift
	 *
	 * @return  string
	 */
	public function getShift()
	{
		return $this->shift;
	}

	/**
	 * Set the value of shift
	 *
	 * @param  string  $shift
	 *
	 * @return  self
	 */
	public function setShift(string $shift)
	{
		$this->shift = $shift;

		return $this;
	}
}
