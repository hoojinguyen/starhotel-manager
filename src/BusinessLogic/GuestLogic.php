<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Guest;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Romi\Domain\Tenant;

class GuestLogic extends BaseLogic
{

	// save and update
	public function createGuest($params)
	{
		//Find profile if exist
		$existGuest = $this->getEntityManager()
			->getRepository(Guest::class)
			->findOneBy(array('idCardNo' => $params['idCardNo']));

		//If not exist, create new guest
		if (!$existGuest) {
			$guestNew = $this->createGuestEntity($params);
			$this->saveOrUpdate($guestNew);
			return $guestNew;
		} else {
			return $existGuest;
		}
	}

	protected function createGuestEntity($params)
	{
		$guest = new Guest();
		$this->setGuestEntity($params, $guest);
		return $guest;
	}

	public function setGuestEntity($params, Guest &$guest)
	{
		if (!$params['idCardIssueDate'] == "") {
			$issueDate = new \DateTime($params['idCardIssueDate']);
		} else {
			$issueDate = null;
		}

		$now = new \DateTime($params['createdAt']);

		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $params['idTenant']));
		$guest->setTenantId($tenantId);

		$expiryDate = new \DateTime($params['idCardExpiryDate']);
		$guest->setName($params['name']);
		$guest->setIdCardNo($params['idCardNo']);
		$guest->setGender($params['gender']);
		$guest->setPhoneNumber($params['phoneNumber']);
		$guest->setAddress($params['address']);
		$guest->setIdCardIssueDate($issueDate);
		$guest->setIdCardExpiryDate($expiryDate);
		$guest->setIdCardIssuePlace($params['idCardIssuePlace']);
		$guest->setYearOfBirth($params['yearOfBirth']);

		$guest->setCreatedAt($now);
		$guest->setUpdatedAt($now);
		$guest->setCreatedBy($params['createdBy']);
		$guest->setUpdatedBy($params['updatedBy']);
	
	}

	public function updateGuest($params)
	{
		$guest = $this->getEntityManager()->getRepository(Guest::class)->findOneBy(array('id' => $params['id']));
		if (!$guest) {
			return false;
		}
		$this->setGuestEntity($params, $guest);
		$this->getEntityManager()->flush();
		return true;
	}	

	//load
	public function loadGuests($params)
	{
		if ($params['sortDir'] == 1) {
			$sortDir = 'ASC';
		} else {
			$sortDir = 'DESC';
		}
		$searchName= $params['searchBy'];
		$criteria = array('name' => $params['searchBy']);
		$defaultPageSize = 10;
		$limit = $params['pageSize'] ? $params['pageSize'] : $defaultPageSize;
		$offset = $limit * $params['pageIndex'];

		$qb = $this->getEntityManager()
			->getRepository(Guest::class)
			->createQueryBuilder('a');

		if ($params['searchBy'] != '') {
			$qb->where('a.name LIKE :searchName');
			$qb->setParameter('searchName', '%' . $searchName . '%');
			$qb->orWhere('a.idCardNo LIKE :searchName');
			$qb->setParameter('searchName', '%' . $searchName . '%');
			$qb->orWhere('a.phoneNumber LIKE :searchName');
			$qb->setParameter('searchName', '%' . $searchName . '%');
		}

		$query = $qb->orderBy('a.' . $params['sortBy'], $sortDir)
			->setMaxResults($limit)
			->setFirstResult($offset)
			->getQuery();
		$result = $query->getResult();	

		$paginator = new Paginator($query, $fetchJoinCollection = true);
		$totalRows = count($paginator);
		return array('data' => $result,'totalRows' => $totalRows);
		// return $result;
	}

	public function getIdGuest($idCardNo)
	{
		$r = $this->getEntityManager()->getRepository(Guest::class)
			->createQueryBuilder('r')
			->select('r.id')
			->where('r.idCardNo = :idCardNo')
			->setParameter('idCardNo', $idCardNo)
			->getQuery()
			->getResult();
		return $r;
	}

	public function loadGuest($guestId)
	{
		return $this->getEntityManager()->getRepository(Guest::class)->createQueryBuilder('g')
		->select('g.id , g.name , g.gender, g.phoneNumber, g.idCardNo, g.yearOfBirth, g.address, g.idCardIssuePlace, (g.idCardIssueDate) as idCardIssueDate, (g.idCardExpiryDate) as idCardExpiryDate')
		->where('g.id = :guestId')
		->setParameter('guestId', $guestId)
		->getQuery()
		->getResult();
	}

	public function deleteGuest($guestId)
	{
		$guest = $this->getEntityManager()
			->getRepository(Guest::class)
			->findOneBy(array('id' => $guestId));
		if ($guest) {
			$this->delete($guest);
			return true;
		}
		return false;
	}
	
	public function getGuestByIdCard($idCardNo)
	{
		$guest = $this->getEntityManager()
			->getRepository(Guest::class)
			->findOneBy(array('idCardNo' => $idCardNo));

		if (!$guest) {
			return false;
		}

		return $guest;
	}

	public function getGuestByTerm($term){
		 return $this->getEntityManager()
			->getRepository(Guest::class)->createQueryBuilder('a')->where('a.idCardNo LIKE :term')->orWhere('a.name LIKE :term')
			->setParameter('term', '%' . $term . '%')
			->setMaxResults(10)
			->getQuery()
			->getResult();
	}



	public function countGuest()
	{
		return $this->getEntityManager()
			->getRepository(Guest::class)
			->count();
	}


}
