<?php
// namespace Authorization;
namespace Romi\Middleware;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;
use Slim\DeferredCallable;
use Doctrine\ORM\EntityManager;
use Romi\Domain\UserRole;
use Romi\Domain\Resource;
use Romi\Domain\UserRolePrivileges;
use Romi\Domain\Privileges;

class Acl extends ZendAcl
{
    private $container;
    private $em;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
        $this->em = $container->get(EntityManager::class);

        $roles = $this->em->getRepository(UserRole::class)->createQueryBuilder('ur')
            ->select('ur.name')
            ->getQuery()
            ->getResult();

        $resources =  $this->em->getRepository(Resource::class)->createQueryBuilder('res')
            ->select('res.name')
            ->getQuery()
            ->getResult();

        $roleResourceActions =  $this->em->getRepository(UserRolePrivileges::class)->createQueryBuilder('urp')
            ->select('ur.name as role , r.name as resource , p.action')
            ->leftJoin(Privileges::class, 'p', 'WITH', 'urp.privilegesId = p.id')
            ->leftJoin(UserRole::class, 'ur', 'WITH', 'urp.userRoleId = ur.id')
            ->leftJoin(Resource::class, 'r', 'WITH', 'r.id=p.resourceId')
            ->getQuery()
            ->getResult();

        foreach ($roles as $role) {
            $this->addRole(strtoupper($role['name']));
        }

        foreach ($resources as $resource) {
            $this->addResource($resource['name']);
        }

        foreach ($roleResourceActions as $res) {
            $this->allow(strtoupper($res['role']), $res['resource'], $res['action']);
        }
    }


    public function __invoke(Request $request, Response $response, $next)
    {
        // if (!isset($_SESSION['user'])) {
        //     return $response->withRedirect('/admin/login');
        // }
        // $role = $_SESSION['user'];
        if(isset($_SESSION['user'])){
            $role = $_SESSION['user'] ;
        }
        else {
            $role = 'CLIENT' ;
        }
        $resource = $request->getAttribute('route')->getName();
        $privileges = $request->getAttribute('route')->getArgument('action');
        $check = $this->isAllowed($role, $resource, $privileges);

        if (!$check) {
            return $response->withJson(['errors' => ["You don't access in this page"]], 422);
        }
        $response = $next($request, $response);
        return $response;
    }
}
