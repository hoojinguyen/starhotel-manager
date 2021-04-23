<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Account;
use Romi\Domain\Profile;
use Entities\User;
use Faker\Provider\zh_TW\DateTime;

class UserLogic extends BaseLogic
{


	public function loadUser($userId)
	{
		$user = $this->getEntityManager()
			->getRepository(Account::class)
			->findOneBy(array('id' => $userId));
		return $user;
	}

	public function loadUsers($params)
	{
		if ($params['sortDir'] == 1) {
			$sortDir = 'ASC';
		} else {
			$sortDir = 'DESC';
		}

		$criteria = array('username' => $params['searchBy']);
		$defaultPageSize = 10;
		$limit = $params['pageSize'] ? $params['pageSize'] : $defaultPageSize;
		$offset = $limit * $params['pageIndex'];

		$qb = $this->getEntityManager()
			->getRepository(Account::class)
			->createQueryBuilder('a');

		if ($params['searchBy'] != '') {
			$qb->where('a.username LIKE :username');
			$qb->setParameter('username', '%' . $criteria['username'] . '%');
		}

		$result = $qb->orderBy('a.' . $params['sortBy'], $sortDir)
			->setMaxResults($limit)
			->setFirstResult($offset)
			->getQuery()
			->getResult();
		return $result;
	}


	public function createUser($params)
	{

		date_default_timezone_set('Asia/Bangkok');
		$date = date_create(date("d-m-Y H:i:s"));

		$params['createdAt'] = $date;
		$params['updatedAt'] = $date;
		$params['lastLogin'] = $date;
		$params['flag'] = 1;

		$this->logger->info(empty($params));
		$existUser = $this->getEntityManager()
			->getRepository(Account::class)
			->findOneBy(array('username' => $params['username']));
		//If not exist, create new user
		if (!$existUser) {
			$user = $this->setUser($params);
			$this->saveOrUpdate($user);
			return true;
		} else {
			return $existUser;
		}
	}

	protected function setUser($params)
	{
		$user = new Account();
		$user->setId($params['id']);
		$user->setUsername($params['username']);
		$user->setPassword($params['password']);
		$user->setCreatedAt($params['createdAt']);
		$user->setLastLogin($params['lastLogin']);
		$user->setUpdatedAt($params['updatedAt']);
		$user->setFlag($params['flag']);
		return $user;
	}

	public function updateUser($params)
	{
		date_default_timezone_set('Asia/Bangkok');
		$date = date_create(date("d-m-Y H:i:s"));
		$params['updatedAt'] = $date;

		$existUser = $this->getEntityManager()
			->getRepository(Account::class)
			->findOneBy(array('id' => $params['id']));

		$existUser->setPassword($params['password']);
		$existUser->setUpdatedAt($params['updatedAt']);
		$this->getEntityManager()->flush();
		return true;
	}

	public function deleteUser($userId)
	{
		$user = $this->getEntityManager()
			->getRepository(Account::class)
			->findOneBy(array('id' => $userId));
		if ($user) {
			$this->delete($user);
			return true;
		}
		return false;
	}
}
