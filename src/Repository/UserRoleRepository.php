<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;
use Romi\Domain\UserRole;

class UserRoleRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }

    public function getRoles(){
        $dql = "SELECT ur.name FROM Romi\Domain\UserRole as ur";
		$query = $this->getEntityManager()->createQuery($dql);
		return $query;
	}
}