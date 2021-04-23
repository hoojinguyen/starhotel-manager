<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class UserController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = array(
			"searchBy" => "",
			"sortBy" => "id",
			"pageSize" => 10,
			"pageIndex" => 0,
			"sortDir" => 1
		);

		$users = $this->getLogic('User')->loadUsers($params);

		// get logic data 	
		return $this->view->render($response, '/admin/user/user.html', [
			'pageHeader' => 'Danh sách tài khoản',
			'pageDescription' => '',
			'users' =>  $users,
			'params' => $params,
			
		]);
	}

	// ajax controller
	public function load(Request $request, Response $response, array $args)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = array(
			'searchBy' => $request->getParam('searchBy'),
			"sortBy" => $request->getParam('sortBy'),
			"pageSize" => $request->getParam('pageSize'),
			"pageIndex" => $request->getParam('pageIndex') - 1,
			"sortDir" => $request->getParam('sortDir')
		);

		$users = $this->getLogic('User')->loadUsers($params);
		if (!$users) {
			return $response->withStatus(201);
		}
		return $response->withJson($users);
	}

	public function save(Request $request, Response $response, array $args)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParam($request);
		// Validation
		$this->validate($params, $response);

		if ($params['id'] == '') {
			$user = $this->getLogic('User')->createUser($params);
		} else {
			$user = $this->getLogic('User')->updateUser($params);
		}

		if ($user === true) {
			return $response->withJson($user, 201);
		} else {
			return $response->withJson($user, 409);
		}
		return 0;
	}


	public function loadUserById(Request $request, Response $response)
	{
		$id = $request->getParam('id');

		$user = $this->getLogic('User')->loadUser($id);

		return $response->withJson($user, 201);
	}

	public function getParam(Request $request)
	{
		$params = array(
			'id' => $request->getParam('userId'),
			'profile_id' => null,
			'username' => $request->getParam('username'),
			'password' => $request->getParam('password'),
			'lastLogin' =>  $request->getParam('lastLogin'),
			'createdAt' => $request->getParam('createdAt'),
			'updatedAt' => $request->getParam('updatedAt'),
			'flag' => $request->getParam('flag'),
		);
		return $params;
	}

	// // Delete User
	public function delete(Request $request, Response $response, array $args)
	{
		$id = $request->getParam('id');
		$user = $this->getLogic('User')->deleteUser($id);
		if (!$user) {
			return $response->withStatus(417);
		} else {
			return  $response->withStatus(201);
		}
	}

	// Validate
	protected function validate($params, $response)
	{
		$validation = $this->validator->validateArray($params, [
			'username' => validator::notEmpty(),
			'password' => validator::notEmpty(),
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
}
