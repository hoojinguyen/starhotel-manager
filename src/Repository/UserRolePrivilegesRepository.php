<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;
use Romi\Domain\UserRolePrivileges;
use Romi\Domain\Privileges;
use Romi\Domain\UserRole;
use Romi\Domain\Resource;

class UserRolePrivilegesRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }

    public function getRoleResourceActions(){
		return $this->getEntityManager()->from(UserRolePrivileges::class)->createQueryBuilder('urp')
        ->select('ur.name as Role , r.name as Resource , p.action as Action')
		->leftJoin(Privileges::class, 'p', 'WITH', 'urp.privilegesId = p.id')
		->leftJoin(UserRole::class, 'ur', 'WITH', 'urp.userRoleId = ur.id')
		->leftJoin(Resource::class, 'r', 'WITH', 'r.id=p.resourceId')
		->getQuery()
		->getResult();
	}
    
}