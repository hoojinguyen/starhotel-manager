<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;
use Romi\Domain\Resource;

class ResourceRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    public function getResources(){
        $dql = "SELECT res.name FROM Romi\Domain\Resource as res";
		$query = $this->getEntityManager()->createQuery($dql);
		return $query;
	}
}