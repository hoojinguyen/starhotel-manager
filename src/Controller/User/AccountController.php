<?php

namespace Romi\Controller\User;

use Romi\Domain\Account;
use Romi\Domain\Profile;
use Romi\Controller\BaseController;
use Romi\Transformer\AccountTransformer;
use Romi\Transformer\GenericTransformer;
use Romi\Validation\Rules\ExistsWhenUpdate;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Shared\ResultObject;

class AccountController extends BaseController {

	// MVC
	public function profile(Request $request, Response $response, array $args){
		$this->logger->info('view profile account info');
		// get logic data 	
		return $this->view->render($response, 'profile.html', [
			'name' =>  'test'
		]);
		// use params this fuction
		// return $this->view->render($response, 'profile.html', [
		// 	'name' =>  $args['name']
		// ]);
	}

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
			return $response->withRedirect("/admin/login?error='Email Password Incorrect'");
		}

		
		if($username=="vanhoinguyen98@gmail.com"){
		}
		else {
			return $response->withRedirect("/admin/login?error=Incorrect email");
		}
		if($password == "123"){
			return $response->withRedirect("/admin/dashboardadmin");
		}
		else {
			return $response->withRedirect("/admin/login?error=Incorrect Password");
		}


	}

	protected function validateLoginRequest($values){
		return $this->validator->validateArray($values,[
			'username' => validator::noWhitespace()->notEmpty(),
			'password' => validator::noWhitespace()->notEmpty(),
		]);
	}
	// API
	public function view(Request $request, Response $response, array $args) {
		$this->logger->info('view account info');
		$accountId = $args['accountId'];
		$account = $this->getLogic('Account')->loadAccount($accountId);
		$result = new \stdClass();
		if (!$account) {
			$result->message = 'No data found';
		} else {
			$result = $this->fractal->createData(new Item($account, new AccountTransformer()))->toArray();
		}

		return $response->withJson($result);
	}

	public function save(Request $request, Response $response, array $args) {
		$this->logger->info('save account info');

		$Id = $args['Id'];

		$params = array(
			'Id' => $request->getParam('id'),
			'email' => $request->getParam('email'),
			'data' => $request->getParam('data'),
			'type' => $request->getParam('type')
		);

		$result = new \stdClass();
		if ((int) $payorId !== $params['payorId']) {
			$result->message = 'No data found';
		} else {
			//Validation
			$validation = $this->validateSaveRequest($params, $payorId);
			if ($validation->failed()) {
				$result->errors = $validation->getErrors();
				return $response->withJson($result, 422);
			}
			// Perform logic
			$account = $this->getLogic('Account')->createAccount($payorId, $params);
			$object = new ResultObject(true, 'account', $account->getId());
			$result = $this->fractal->createData(new Item($object, new GenericTransformer()))->toArray();
		}

		return $response->withJson($result);
	}
	
	public function update(Request $request, Response $response, array $args) {
		$this->logger->info('update account info');
		
		$accountId = $args['accountId'];
		
		$params = array(
			'payorId' => $request->getParam('payor_id'),
			'data' => $request->getParam('data')
		);
		
		// Perform logic
		$isSuccess  = $this->getLogic('Account')->updateAccount($accountId, $params);
		$object = new ResultObject($isSuccess);
		$result = $this->fractal->createData(new Item($object, new GenericTransformer()))->toArray();

		return $response->withJson($result);
	}
	
	public function delete(Request $request, Response $response, array $args){
		
		$this->logger->info('delete account info');
		
		$accountId = $args['accountId'];
		$isSuccess = $this->getLogic('Account')->deleteAccount($accountId);
		$object = new ResultObject($isSuccess);
		$result = $this->fractal->createData(new Item($object, new GenericTransformer()))->toArray();
		
		return $response->withJson($result);
	}

	protected function validateSaveRequest($values, $payorId) {
		$repository = $this->getEntityManager()->getRepository(Profile::class);

		return $this->validator->validateArray($values, [
					'email' => validator::optional(
							validator::noWhitespace()
									->notEmpty()
									->email()
									->existsWhenUpdate($repository, 'payorId', $payorId, 'email')
					)
		]);
	}

}
