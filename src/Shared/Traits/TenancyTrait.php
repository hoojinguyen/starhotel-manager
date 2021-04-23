<?php

namespace Romi\Shared\Traits;

trait TenancyTrait{
	
	/**
	 * @var string
	 *
	 * @ORM\Column(type="guid", name="tenant_id", nullable=true)
	 */
	private $tenantId;
	
	public function setTenantId($tenantId) {
		$this->tenantId = $tenantId;
		return $this;
	}
}
