<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class EmployeeController extends BaseController {
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response) {
		if (!isset($request))
			return $response->withStatus(501);

		$employees = $this->getLogic('Employee')->loadEmployee();
		var_dump($employees);
		return $this->view->render($response, '/admin/employee/employee.html', [
			'pageHeader' => 'Danh Sách Nhân Viên',
            'pageDescription' => '',
			'employees' => $employees,
			'usertypes' => $_SESSION['usertype'],
			
		]);
	}
	
	// Save employee
	public function saveEmployee(Request $request, Response $response, array $args)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getEmployeeParam($request);
		//Validation
		$this->validate($params, $response);
		// Perform logic
		if ($params['id'] == '') {
			$employee = $this->getLogic('Employee')->createEmployee($params);
		} else {
			$employee = $this->getLogic('Employee')->updateEmployee($params);
		}

		if ($employee === true) {
			return $response->withJson($employee, 201);
		} else {
			return $response->withJson($employee, 409);
		}
	}

	public function getEmployeeParam(Request $request)
	{
		$now = date_create()->format('Y-m-d');
		$dayToWork = date_format(date_create($request->getParam('dayToWork')), 'd-m-Y H:i:s');
		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('nameEmployee'),
			'gender' => $request->getParam('gender'),
			'phoneNumber' => $request->getParam('phoneNumber'),
			'idCardNo' => $request->getParam('idCardNo'),
			'yearOfBirth' => $request->getParam('yearOfBirthEmployee'),
			'dayToWork' => $dayToWork,
			'address' => $request->getParam('address'),
			'shift' => $request->getParam('shift'),
			'position' => $request->getParam('position'),
			'code' => $request->getParam('codeEmployee'),
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $now ,
			'updatedAt' => $now ,
			'idTenant' => 1

		);
		return $params;
	}


	protected function validate($params, $response)
	{
		$validation = $this->validator->validateArray($params, [
			'name' => validator::notEmpty(),
			'gender' => validator::notEmpty(),
			'phoneNumber' => validator::notEmpty()->phone(),
			'idCardNo' => validator::notEmpty(),
			'address' => validator::stringType(),
			'shift' => validator::notEmpty(),
			'position' => validator::notEmpty(),
			'code' => validator::notEmpty(),
			'dayToWork' => validator::date()->notEmpty(),
			'yearOfBirth' => validator::date()->notEmpty(),
		
		]);

		if ($validation->failed()) {
			$result->errors = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}

	public function loadEmployeeById(Request $request, Response $response)
	{
		$id = $request->getParam('id');

		$employee = $this->getLogic('Employee')->loadEmployeeById($id);

		return $response->withJson($employee, 201);
	}

	public function deleteEmployee(Request $request, Response $response, array $args)
	{
		$id = $request->getParam('id');

		$employee = $this->getLogic('Employee')->deleteEmployee($id);

		if ($employee) {
			return $response->withStatus(201);
		} else {
			return $response->withStatus(417);
		}
	}

	public function loadEmployee(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$employees = $this->getLogic('Employee')->loadEmployee();

		if (!$employees) {
			return $response->withStatus(201);
		}
		return $response->withJson($employees);
	}

}
