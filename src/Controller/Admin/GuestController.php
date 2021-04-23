<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class GuestController extends BaseController
{
	protected $templatePath = '/admin/';

	// mvc: Guest List
	public function view(Request $request, Response $response)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}

		$params = array(
			"searchBy" => "",
			"sortBy" => "id",
			"pageSize" => 10,
			"pageIndex" => 0,
			"sortDir" => 1
		);

		$guests = $this->getLogic('Guest')->loadGuests($params);

		// return $this->view->render($response, '/admin/guest/guest.html', [
		// 	'pageHeader' => 'Khách hàng',
		// 	'pageDescription' => '',
		// 	'guests' => $guests,
		// 	'params' => $params
		// ]);

		$page = $params['pageIndex'];
		$limit = $params['pageSize'];
		$count = $guests['totalRows'];
		$currentPageRecords = $limit;
		if ($count < $limit) {
			$currentPageRecords = $count;
		}

		$data = [
			'pageHeader' => 'Danh Sách Khách Hàng',
			'pageDescription' => '',
			'pagination'    => [
				'needed'        => $count > $limit,
				'count'         => $count,
				'page'          => $page,
				'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
				'limit'         => $limit,
			],
			'guests' => $guests['data'],
			'limits' => $limit,
			'counts'  => $count,
			'currentPageRecords' => $currentPageRecords,

		];

		return $this->view->render($response, '/admin/guest/guest.html', $data);
	}

	// ajax controller
	public function load(Request $request, Response $response, array $args)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}

		$params = array(
			'searchBy' => $request->getParam('searchBy'),
			"sortBy" => $request->getParam('sortBy'),
			"pageSize" => $request->getParam('pageSize'),
			"pageIndex" => $request->getParam('pageIndex'),
			"sortDir" => $request->getParam('sortDir')
		);

		$guests = $this->getLogic('Guest')->loadGuests($params);

		$page = $params['pageIndex'];
		$limit = $params['pageSize'];
		$count = $guests['totalRows'];

		$paginationInfo = [
			'needed'        => $count,
			'count'         => $count,
			'page'          => $page,
			'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
			'limit'         => $limit,
		];

		$data = [
			'pageHeader' => 'Danh Sách Khách Hàng',
			'pageDescription' => '',
			'paginationInfo' => $paginationInfo,
			'guests' => $guests['data'],
			'limits' => $limit,
			'counts'  => $count,
		];
		if (!$guests) {
			return $response->withStatus(201);
		}
		return $response->withJson($data, 200);
	}

	// Save guest
	public function save(Request $request, Response $response, array $args)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getGuestParam($request);

		//Validation
		$this->validate($params, $response);
		// Perform logic
		if ($params['action'] === 'add') {
			$guest = $this->getLogic('Guest')->createGuest($params);
		} else {
			$guest = $this->getLogic('Guest')->updateGuest($params);
		}

		if ($guest) {
			return $response->withJson($guest, 201);
		} else {
			return $response->withJson($guest, 409);
		}
	}

	public function getGuestParam(Request $request)
	{
		date_default_timezone_set('Asia/Bangkok');
		$idCardIssueDate = date_format(date_create($request->getParam('idCardIssueDate')), 'd-m-Y');
		$idCardExpiryDate  = date_format(date_create($request->getParam('idCardExpiryDate')), 'd-m-Y');
		$now = date_create()->format('Y-m-d');

		$params = array(
			'action'=>  $request->getParam('action'),
			'id' => $request->getParam('id'),
			'name' => $request->getParam('name'),
			'gender' => $request->getParam('gender'),
			'phoneNumber' => $request->getParam('phoneNumber'),
			'idCardNo' => $request->getParam('idCardNo'),
			'idCardIssueDate' =>$idCardIssueDate,
			'idCardExpiryDate' => $idCardExpiryDate,
			'idCardIssuePlace' => $request->getParam('idCardIssuePlace'),
			'yearOfBirth' => $request->getParam('yearOfBirth'),
			'address' => $request->getParam('address'),
			'idTenant' => 1,
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $now,
			'updatedAt' => $now,
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
			'idCardIssueDate' => validator::optional(validator::date()),
			'idCardExpiryDate' => validator::date()->notEmpty(),
			'idCardIssuePlace' => validator::stringType(),
			'yearOfBirth' => validator::optional(validator::numeric()),
			'address' => validator::stringType(),
		]);

		if ($validation->failed()) {
			$result->errors = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}

	public function loadGuestById(Request $request, Response $response)
	{
		$id = $request->getParam('id');
		$guest = $this->getLogic('Guest')->loadGuest($id);
		if (!$guest) {
			return $response->withJson($guest, 409);
		}
		return $response->withJson($guest, 201);
	}

	public function delete(Request $request, Response $response, array $args)
	{
		$id = $request->getParam('id');
		// $id  = $request->getQueryParam('id');
		$guest = $this->getLogic('Guest')->deleteGuest($id);
		if (!$guest) {
			return $response->withJson($guest, 409);
		}
		return $response->withJson($guest, 201);
	}
	public function count(Request $request, Response $response, array $args)
	{
		$count = $this->getLogic('guest')->countGuest();
		if ($count >= 0) {
			return $response->withJson(true, 201);
		}
		return $response->withJson(false, 417);
	}
}
