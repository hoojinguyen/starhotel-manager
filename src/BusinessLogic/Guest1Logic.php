<?php

namespace Romi\BusinessLogic;


use Romi\Shared\Cryptography;
use Romi\Domain\Tenant;
use Romi\Domain\Guest;

class Guest1Logic extends BaseLogic {

	
	public function loadGuest()
	{
		$r = $this->getEntityManager()->getRepository(Guest::class)
			->createQueryBuilder('r')
			->select('r.id, r.name, r.gender, r.phoneNumber, r.idCardNo, (r.idCardIssueDate) as idCardIssueDate , (r.idCardExpiryDate) as idCardExpiryDate, r.idCardIssuePlace ,r.yearOfBirth, r.address')
			->getQuery()
			->getResult();
		return $r;
	}

	





	


}
