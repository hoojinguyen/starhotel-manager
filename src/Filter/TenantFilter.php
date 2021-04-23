<?php

namespace Romi\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class TenantFilter extends SQLFilter {

	public function addFilterConstraint(ClassMetadata $metadata, $table) {
		if ($this->isTenancy($metadata->rootEntityName)) {
			return $table . '.tenant_id = ' . $this->getParameter('TenantId') ;
		}
		return '';
	}

	private function isTenancy($entity) {
		return array_key_exists('Romi\Shared\Traits\TenancyTrait', class_uses($entity));
	}

}
