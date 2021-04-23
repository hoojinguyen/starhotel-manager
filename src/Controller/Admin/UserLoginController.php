<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class UserLoginController extends BaseController {
	protected $templatePath = '/admin/';
    public function login(Request $request,Response $response, array $args){
		$error = $request->getQueryParam('error');
		// var_dump($error);
		$this->view->render($response,'/admin/login.html',[
			'error' => $error
		]);
		return $response;
	}


	


	public function validateAccount(Request $request, Response $response, array $args){
		// get param
		// validate param , email not null, is email? , password not null
		// check compare password with email
		// if success redirect home , else redirect login + mess error
		$username = $request->getParam('username');
		$password = $request->getParam('password');
		$requestParams = ['username' => $username , 'password' => $password];

		$validation = $this->validateLoginRequest($requestParams);
		if($validation->failed()){
			return $response->withRedirect("/admin/login?error=Tài khoản hoặc mật khẩu không chính xác !");
		}
		$res = $this->getLogic('UserLogin')->checkUserLogin($username,$password);
		if($res == "password"){
			return $response->withRedirect("/admin/login?error=Mật khẩu không chính xác!");
		}
		if($res == "username"){
			
			return $response->withRedirect("/admin/login?error=Tên đăng nhập không chính xác !");
		}
		if($res == "admin"){
			$_SESSION["usertype"]=$res;
			return $response->withRedirect("/admin/dashboard");

		}
		if($res == "staff"){
			$_SESSION["usertype"]=$res;
			return $response->withRedirect("/admin/dashboard");

		}


	}
	
	protected function validateLoginRequest($values){
		return $this->validator->validateArray($values,[
			'username' => validator::noWhitespace()->notEmpty(),
			'password' => validator::noWhitespace()->notEmpty(),
		]);
	}
}
