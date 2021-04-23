<?php

namespace Romi\Shared\EventSubscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Romi\Shared\TenantScopePerRequest;

class InsertTenancyEventSubscriber implements EventSubscriber {

	public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
		
		
    }
	
	public function prePersist(LifecycleEventArgs $eventArgs)
	{
		$entity = $eventArgs->getEntity();
		if ($this->isTenancy($entity)) {
			
			if ($tenantId = TenantScopePerRequest::getTenantScope()){
				$entity->setTenantId($tenantId);
			}
		}
	}
	
	private function isTenancy($entity) {
		return array_key_exists('Romi\Shared\Traits\TenancyTrait', class_uses($entity));
	}

}
