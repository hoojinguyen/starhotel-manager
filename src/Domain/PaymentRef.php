<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="payment_ref")
 * @ORM\Entity(repositoryClass="Romi\Repository\PaymentRefRepository")
 */
class PaymentRef implements \JsonSerializable
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
	 * @ORM\ManyToOne(targetEntity="Payment")
	 * @ORM\JoinColumn(name="payment_id", referencedColumnName="id", nullable=true)
	 */
	private $paymentId;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $amount;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $quarter;
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $month;
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $year;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=true)
	 */
	private $tenantId;


	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'orderId' => $this->orderId,
			'amount' => $this->amount
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
	 * Get the value of paymentId
	 */ 
	public function getPaymentId()
	{
		return $this->paymentId;
	}

	/**
	 * Set the value of paymentId
	 *
	 * @return  self
	 */ 
	public function setPaymentId($paymentId)
	{
		$this->paymentId = $paymentId;

		return $this;
	}

	/**
	 * Get the value of amount
	 *
	 * @return  integer
	 */ 
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Set the value of amount
	 *
	 * @param  integer  $amount
	 *
	 * @return  self
	 */ 
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	/**
	 * Get the value of quarter
	 *
	 * @return  integer
	 */ 
	public function getQuarter()
	{
		return $this->quarter;
	}

	/**
	 * Set the value of quarter
	 *
	 * @param  integer  $quarter
	 *
	 * @return  self
	 */ 
	public function setQuarter($quarter)
	{
		$this->quarter = $quarter;

		return $this;
	}

	/**
	 * Get the value of month
	 *
	 * @return  integer
	 */ 
	public function getMonth()
	{
		return $this->month;
	}

	/**
	 * Set the value of month
	 *
	 * @param  integer  $month
	 *
	 * @return  self
	 */ 
	public function setMonth($month)
	{
		$this->month = $month;

		return $this;
	}

	/**
	 * Get the value of year
	 *
	 * @return  integer
	 */ 
	public function getYear()
	{
		return $this->year;
	}

	/**
	 * Set the value of year
	 *
	 * @param  integer  $year
	 *
	 * @return  self
	 */ 
	public function setYear($year)
	{
		$this->year = $year;

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
