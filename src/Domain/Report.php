<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="Romi\Repository\ReportRepository")
 */
class Report implements \JsonSerializable
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
	 *
	 *
	 * @ORM\Column(name = "report_time",type="datetime", nullable=true)
	 */
	private $reportTime;

	/**
	 * @ORM\ManyToOne(targetEntity="Tenant")
	 * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
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
	 * Get the value of reportTime
	 */
	public function getReportTime()
	{
		return $this->reportTime;
	}

	/**
	 * Set the value of reportTime
	 *
	 * @return  self
	 */
	public function setReportTime($reportTime)
	{
		$this->reportTime = $reportTime;

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
