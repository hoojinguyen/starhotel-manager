<?php
namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;

class CustomerController extends BaseController
{

	protected $templatePath = '/admin/';

	public function view(Request $request, Response $response)
	{

		if (!isset($request)) {
			return $response->withStatus(501);
		}
		
		$params = array(
			"searchBy" => "",
			"sortBy" => "id",
			"pageSize" => 2,
			"pageIndex" => 0,
			//page
			"sortDir" => 1
		);

		$customers = $this->getLogic('Customer')->loadCustomers($params);
		
		
		$page = $params['pageIndex'];
		$limit = $params['pageSize'];
		$count = $customers['totalRows'];
		
		$data = [
			'pageHeader' => 'Customer',
			'pageDescription' => '',
			'pagination'    => [
				'needed'        => $count > $limit,
				'count'         => $count,
				'page'          => $page,
				'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
				'limit'         => $limit,
			],
			'customers' => $customers['data']
		];
		return $this->view->render($response, '/admin/customer/customer.html', $data);
	}

	public function load(Request $request, Response $response, array $args)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}

		$pageIndex = $request->getParam('pageIndex');

		$params = array(
			"searchBy" => "",
			"sortBy" => "id",
			"pageSize" => 2,
			"pageIndex" => $pageIndex,
			"sortDir" => 1
		);

		//customers contains list customers and total rows
		$customers = $this->getLogic('Customer')->loadCustomers($params);
		
		
		$page = $params['pageIndex'];
		$limit = $params['pageSize'];
		$count = $customers['totalRows'];
		
		// $pagination = [
		// 	'needed'        => $count > $limit,
		// 	'count'         => $count,
		// 	'page'          => $page,
		// 	'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
		// 	'limit'         => $limit,
		// ];
		
		
		
		//$pagingHtml = $this->view->render($response,'/admin/pagination.html',$pagination);
		
		$paginationInfo = [
			'needed'        => $count > $limit,
			'count'         => $count,
			'page'          => $page,
			'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
			'limit'         => $limit,
		];

		$data = [
			'pageHeader' => 'Customer',
			'pageDescription' => '',
			'paginationInfo' => $paginationInfo,
			'customers' => $customers['data']
		];

		if (!$customers) {
			return $response->withStatus(201);
		}

		return $response->withJson($data,200);
	}

	public function save(Request $request, Response $response, array $args)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}

		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('name'),
			'code' => $request->getParam('code'),
			'active' => $request->getParam('active')
		);

		$this->validate($params, $response);

		$customer = $this->getLogic('Customer')->createCustomer($params);

		if ($customer) {
			return $response->withJson($customer, 201);
		} else {
			return $response->withJson($customer, 409);
		}
	}

	protected function validate($params, $response)
	{
		$validation = $this->validator->validateArray($params, [
			'name' => validator::notEmpty(),
			'code' => validator::notEmpty(),
			'active' => validator::notEmpty(),
		]);

		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}

	public function LoadCustomerById(Request $request, Response $response)
	{
		$id = $request->getParam('id');
		$customer = $this->getLogic('Customer')
			->loadCustomer($id);
		return $response->withJson($customer, 201);
	}

	public function update(Request $request, Response $response, array $args)
	{
		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('name'),
			'code' => $request->getParam('code'),
			'active' => $request->getParam('active')
		);
		$this->validate($params, $response);

		$customer = $this->getLogic('Customer')->updateCustomer($params);

		return $response->withJson($customer, 201);
	}

	public function delete(Request $request, Response $response, array $args)
	{
		$id = $request->getParam('id');

		$customer = $this->getLogic('Customer')->deleteCustomer($id);

		if ($customer) {
			return $response->withJson(true, 201);
		} else {
			return $response->withJson(false, 417);
		}
	}

	public function count(Request $request, Response $response, array $args)
	{
		$count = $this->getLogic('Customer')->countCustomer();
		if ($count >= 0) {
			return $response->withJson(true, 201);
		} else {
			return $response->withJson(false, 417);
		}
	}
}