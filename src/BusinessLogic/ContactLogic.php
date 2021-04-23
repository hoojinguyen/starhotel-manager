<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Contact;
use Romi\Domain\Tenant;

class ContactLogic extends BaseLogic
{
    public function createContact($params)
	{
			$contact = $this->createContactEntity($params);
            $this->saveOrUpdate($contact);
            return $contact;
	
	}
    
    protected function createContactEntity($params)
	{
		$contact = new Contact();
		$this->setContactEntity($params, $contact);
		return $contact;
	}

	public function setContactEntity($params, Contact &$contact)
	{
        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $params['idTenant']));
        $contact->setName($params['nameContact']);
		$contact->setPhoneNumber($params['phoneNumberContact']);
		$contact->setTenantId($tenantId);
		$contact->setEmail($params['emailContact']);
		$contact->setCompany("");
		$contact->setSource("");
		
    }

    
}
