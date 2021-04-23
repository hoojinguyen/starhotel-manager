<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Table(name="guest")
 * @ORM\Entity(repositoryClass="Romi\Repository\GuestRepository")
 */
class Guest implements \JsonSerializable
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
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=true)
	 */
	private $tenantId;

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
	 * @ORM\Column(name = "phone_number",type="string", nullable=true, length = 20)
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
	 * @ORM\Column(name = "id_card_expiry", type="date", nullable=true)
	 */
	private $idCardExpiryDate;

	/**
	 *
	 *
	 * @ORM\Column(name = "id_card_isssue", type="date", nullable=true)
	 */
	private $idCardIssueDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name = "id_card_place", type="string", nullable=true)
	 */
	private $idCardIssuePlace;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name = "year_birth", type="integer", nullable=true)
	 */
	private $yearOfBirth;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=256, nullable=true)
	 */
	private $address;

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
	 * Get the value of idCardExpiryDate
	 */
	public function getIdCardExpiryDate()
	{
		return $this->idCardExpiryDate;
	}

	/**
	 * Set the value of idCardExpiryDate
	 *
	 * @return  self
	 */
	public function setIdCardExpiryDate($idCardExpiryDate)
	{
		$this->idCardExpiryDate = $idCardExpiryDate;

		return $this;
	}

	/**
	 * Get the value of idCardIssueDate
	 */
	public function getIdCardIssueDate()
	{
		return $this->idCardIssueDate;
	}

	/**
	 * Set the value of idCardIssueDate
	 *
	 * @return  self
	 */
	public function setIdCardIssueDate($idCardIssueDate)
	{
		$this->idCardIssueDate = $idCardIssueDate;

		return $this;
	}

	/**
	 * Get the value of idCardIssuePlace
	 *
	 * @return  string
	 */
	public function getIdCardIssuePlace()
	{
		return $this->idCardIssuePlace;
	}

	/**
	 * Set the value of idCardIssuePlace
	 *
	 * @param  string  $idCardIssuePlace
	 *
	 * @return  self
	 */
	public function setIdCardIssuePlace(string $idCardIssuePlace)
	{
		$this->idCardIssuePlace = $idCardIssuePlace;

		return $this;
	}

	/**
	 * Get the value of yearOfBirth
	 *
	 * @return  integer
	 */
	public function getYearOfBirth()
	{
		return $this->yearOfBirth;
	}

	/**
	 * Set the value of yearOfBirth
	 *
	 * @param  integer  $yearOfBirth
	 *
	 * @return  self
	 */
	public function setYearOfBirth($yearOfBirth)
	{
		$this->yearOfBirth = $yearOfBirth;

		return $this;
	}



	/**
	 * Get the value of name
	 *
	 * @return  string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Set the value of name
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
	 * Set the value of createdBy
	 *
	 * @param integer $createdBy
	 *
	 * @return self
	 */
	public function setCreatedBy($createdBy)
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
	 * @param integer $updatedBy
	 *
	 * @return self
	 */
	public function setUpdatedBy($updatedBy)
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
			'gender' => $this->gender,
			'phoneNumber' => $this->phoneNumber,
			'idCardNo' => $this->idCardNo,
			'idCardIssueDate' => $this->idCardIssueDate,
			'idCardExpiryDate' => $this->idCardExpiryDate,
			'idCardIssuePlace' => $this->idCardIssuePlace,
			'yearOfBirth' => $this->yearOfBirth,
			'address' => $this->address,
		];
	}
}
