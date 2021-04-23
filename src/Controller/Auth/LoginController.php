<?php

namespace Romi\Controller\Auth;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class LoginController extends BaseController {

	public function getLogin(Request $request,Response $response, array $args){
		return $this->view->render($response, '/admin/login.html');
	}

	public function postLogin(Request $request, Response $response) {

		$username = $request->getParam('username');
		$password = $request->getParam('password');
		$requestParams = ['username' => $username, 'password' => $password];

		$validation = $this->validateLoginRequest($requestParams);
		if ($validation->failed()) {
			// return $response->withJson(['errors' => $validation->getErrors()], 422);
			return $this->view->render($response, '/admin/login.html', [
				'error' => $validation->getErrors(),
	
			]);
        }
		
		if ($user = $this->auth->attempt($requestParams['username'], $requestParams['password'])) {
			//$tenant->setToken($this->auth->generateToken($tenant));
			//$data = $this->fractal->createData(new Item($tenant, new AuthorizedTenantTransformer()))->toArray();
			return $response->withRedirect('/admin/dashboard');
		}

		return $this->view->render($response, '/admin/login.html', [
			'error' => 'Tên đăng nhập hoặc mật khẩu không chính xác!',
		]);
	}

	protected function validateLoginRequest($values) {
		return $this->validator->validateArray($values, [
					'username' => validator::noWhitespace()->notEmpty(),
					'password' => validator::noWhitespace()->notEmpty(),
		]);
	}


	
	public function postLogout(Request $request,Response $response, array $args){
		$this->auth->logout();
		return $response->withRedirect('/admin/login');
	}
}
